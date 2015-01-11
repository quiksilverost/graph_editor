<table class="table table-hover table-striped table-bordered">
    <thead>
    <tr>
        <th>Имя</th>
        <th>Опции</th>
    </tr>
    </thead>
    <tbody>
    <? foreach($data['nodes'] as $node) { ?>
    <tr id="nodeRow_<?= $node['id'] ?>">
        <td><?= $node['name'] ?></td>
        <td width="90px">
            <a class="btn btn-info" href="?c=Graph&a=edit&id=<?= $node['id'] ?>"><i class="icon-pencil icon-white"></i></a>
            <a class="btn btn-danger confirm-delete" data-id="<?= $node['id'] ?>" data-toggle="modal" href="#"><i class="icon-trash icon-white"></i></a>
        </td>
    </tr>
    <? } ?>
    </tbody>
</table>
<div class="text-right">
    <a href="?c=Graph&a=edit" class="btn btn-success">Добавить узел</a>
</div>

<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">&nbsp;</h3>
    </div>
    <div class="modal-body">
        <p>Вы действительно хотите удалить узел?</p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Отмена</button>
        <a class="btn btn-danger">Удалить</a>
    </div>
</div>

<script type="text/javascript">
    $('#myModal .btn-danger').on('click', function(e) {
        $.ajax({
            url: "?c=Graph&a=delete&id="+$('#myModal').data('href'),
            dataType: 'json',
        }).done(function() {
            $('#nodeRow_'+$('#myModal').data('href')).remove();
            $('#myModal').modal('hide');
        });
    });

   $('.confirm-delete').on('click', function(e) {
        e.preventDefault();

        var id = $(this).data('id');
        $('#myModal').data('href', id).modal('show');
    });
</script>