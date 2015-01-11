<?php

class GraphController extends Controller {
    public function index() {
        $nodes = sql::db()->query("select * from Nodes")->fetchAll();



        $this->render([
            'nodes' => $nodes
        ]);
    }

    public function edit() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $is_binded = (isset($_POST['is_binded']))?$_POST['is_binded']:[];

            try {
                sql::db()->beginTransaction();

                if (empty($_GET['id'])) {
                    $query = sql::db()->prepare("INSERT INTO Nodes(id, name) VALUES (NULL, :name)");
                    $query->bindParam(':name', $_POST['name']);
                    $query->execute();
                    $id = sql::db()->lastInsertId();
                } else {
                    $id = $_GET['id'];

                    $query = sql::db()->prepare("UPDATE Nodes SET name = :name WHERE id = :id");
                    $query->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
                    $query->bindParam(':name', $_POST['name']);
                    $query->execute();

                    $query = sql::db()->prepare("DELETE FROM Edges WHERE in_node = :id OR out_node = :id");
                    $query->bindParam(':id', $id, PDO::PARAM_INT);
                    $query->execute();
                }

                foreach (array_keys($is_binded) as $out_node) {
                    $query = sql::db()->prepare("INSERT INTO Edges(id, in_node, out_node, length) VALUES(NULL, :in_node, :out_node, :length)");
                    $query->bindParam(':in_node', $id, PDO::PARAM_INT);
                    $query->bindParam(':out_node', $out_node, PDO::PARAM_INT);
                    $query->bindParam(':length', $_POST['weight'][$out_node], PDO::PARAM_INT);
                    $query->execute();
                }

                sql::db()->commit();
            } catch(Exception $e) {

                sql::db()->rollBack();
            }

            header("Location:/");
        } else {
            $graph = new Graph();

            $data = [
                'id' => '',
                'name' => '',
                'bindedEdges' => [],
                'nodes' => $graph->nodes,
                'edges' => $graph->edges,
            ];

            if (!empty($_GET['id'])) {
                $data['id'] = $_GET['id'];
                $data['name'] = $graph->nodes[$_GET['id']]['name'];
                $data['bindedEdges'] = $graph->edges[$_GET['id']];
                unset($data['nodes'][$_GET['id']]);

                $data['cycles'] = $this->checkPossibleEdges($data['id'], $graph->edges, $data['nodes']);
            }

            $this->render($data);
        }
    }

    public function delete() {
        try {
            sql::db()->beginTransaction();

            $query = sql::db()->prepare("DELETE FROM Edges WHERE in_node = :id OR out_node = :id");
            $query->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
            $query->execute();

            $query = sql::db()->prepare("DELETE FROM Nodes WHERE id = :id");
            $query->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
            $query->execute();

            sql::db()->commit();

        } catch(Exception $e) {

            sql::db()->rollBack();
        }

        $this->ajaxReslut(true);
    }

    public function checkGraph() {
        $id = $_GET['id'];
        $newEdges = [];
        if(isset($_GET['edges'])) {
            $newEdges = $_GET['edges'];
        }

        $newGraph = new Graph();
        $nodes = $newGraph->nodes;
        $edges = [];

        foreach($nodes as $node_id => $node) {
            $edges[$node_id] = [];
        }


        // Generate the graph without edges of edited node
        if(empty($id)) {
            $edges = $newGraph->edges;
            $id = max(array_keys($edges)) + 1;
        } else {
            unset($nodes[$id]);
            $query = sql::db()->prepare("SELECT * FROM Edges WHERE in_node != :id OR out_node != :id");
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetchAll();

            foreach ($result as $row) {
                // two way binding
                $edges[$row['in_node']][$row['out_node']] = $row['length'];
                $edges[$row['out_node']][$row['in_node']] = $row['length'];
            }
        }

        // Adding new edges to the existing graph
        foreach($newEdges as $newEdge) {
            $edges[$newEdge][$id] = 1;
            $edges[$id][$newEdge] = 1;
        }



        // Find circles and return result
        $this->ajaxReslut($this->checkPossibleEdges($id, $edges, $nodes));
    }

    /**
     * Check possible edges from the node to another nodes without generating circles.
     */
    private function checkPossibleEdges($id_from, $initGraph, $nodes) {
        $treeTraversing = new TreeTraversing();
        $cycles = [];

        foreach($nodes as $nodeId => $node) {
            $potentialGraph = $initGraph;

            $potentialGraph[$id_from][$nodeId]=1;
            $potentialGraph[$nodeId][$id_from]=1;

            $treeTraversing->edges = $potentialGraph;

            $cycles[$nodeId] = $treeTraversing->GraphCycleSearch($id_from);
        }

        return $cycles;
    }
}