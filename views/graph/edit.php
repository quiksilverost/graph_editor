<?
if(empty($_GET['id'])){
    $title = 'Добавление узла';
} else {
    $title = 'Редактирование узла';
}

?>
<h4><?= $title ?></h4>
<form method="post" class="form-horizontal" id="nodesForm" style="position: absolute;">
    <div class="control-group">
        <label class="control-label" for="btnName">Название:</label>
        <div class="controls">
            <input type="text" id="btnName" name="name" value="<?= $data['name'] ?>">
        </div>
    </div>
    <div class="control-label">Связи:</div>
    <?  foreach($data['nodes'] as $nodeId => $node) { ?>
    <div class="control-group">
        <div class="controls" id="control_<?=$nodeId?>" style="height: 30px">
            <span style="margin-right: 5px;"><?= $node['name'] ?></span>
            <span class="node-controls">
                <input type="checkbox" data-id="<?=$nodeId?>" name="is_binded[<?=$nodeId?>]">
                <span class="weight-control" style="visibility: hidden;">вес <input type="text" data-id="<?=$nodeId?>" name="weight[<?=$nodeId?>]" value=""></span>
            </span>
            <span class="cycle-message"></span>
        </div>
    </div>
    <?  } ?>
    <div class="control-group text-right" style="margin-top: 20px;">
        <button type="submit" class="btn btn-primary">Сохранить</button>
        <a class="btn" href="?c=Graph">Отмена</a>
    </div>
</form>

<script type="text/javascript">
    var initData = <?= json_encode($data); ?>;

    $( document ).ready(function() {
        for (var node_id in initData['bindedEdges']) {
            $('#control_' + node_id + ' .weight-control').css("visibility", "visible");
            $('#control_' + node_id + ' input[name="is_binded[' + node_id + ']"]').prop('checked', true);
            $('#control_' + node_id + ' input[name="weight[' + node_id + ']"]').val(initData['bindedEdges'][node_id]);
        }
        updateForm();

        function updateForm() {
            for (var node_id in initData['cycles']) {
                if (initData['cycles'][node_id] != null) {
                    $('#control_' + node_id + ' .node-controls').hide();
                    $('#control_' + node_id + ' .cycle-message').html('Создание связи приведет к циклу');
                    //$('#control_'+node_id+' .cycle-message').html(initData['nodes'][[initData['nodes'][node_id]['cycle']]]['name']);
                } else {
                    $('#control_' + node_id + ' .node-controls').show();
                    $('#control_' + node_id + ' .cycle-message').html('');
                }
            }
        }

        $('#nodesForm input[type=checkbox]').change(function() {
            var selectedEdges= [];
            for(var node_id in initData['nodes']) {
                var checkbox = $('#control_' + node_id + ' input[name="is_binded[' + node_id + ']"]');
                if(checkbox.prop('checked')) {
                    selectedEdges.push(node_id);
                }
            }

            $.ajax({
                url: "?c=Graph&a=checkGraph",
                dataType: 'json',
                data: {id: initData['id'], edges: selectedEdges }
            }).done(function(response) {
                initData['cycles'] = response;
                updateForm();
            });

            if(this.checked) {
                $('#control_' + $(this).data('id') + ' .weight-control').css("visibility", "visible");
            } else {
                $('#control_' + $(this).data('id') + ' .weight-control').css("visibility", "hidden");
            }
        });

    });
</script>