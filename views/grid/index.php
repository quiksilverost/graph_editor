<table class="table table-bordered">
    <tr>
        <td></td>
        <? foreach($data['graph']->nodes as $node) { ?>
        <td><?= $node['name'] ?></td>
        <? } ?>
    </tr>
    <? foreach($data['graph']->nodes as $node_x_key => $node_x) { ?>
    <tr>
        <td><?= $node_x['name'] ?></td>
        <? foreach($data['graph']->nodes as $node_y_key => $node_y) { ?>
        <td><?
            if(!empty($data['graph']->edges[$node_x_key][$node_y_key])) {
                echo $data['graph']->edges[$node_x_key][$node_y_key];
            } else if($node_x_key==$node_y_key) {
                echo 0;
            }
            ?></td>
        <? } ?>
    </tr>
    <? } ?>
</table>