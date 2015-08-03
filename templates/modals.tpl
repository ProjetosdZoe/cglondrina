{{#info}}
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-black">
            <strong>{{{title}}}</strong> {{{text}}}
        </div>
    </div>
</div>
{{/info}}


{{#modal-form}}
<form action="{{action}}" method="{{method}}" enctype="multipart/form-data" accept-charset="UTF-8" class="form-horizontal validate" novalidate="novalidate" >
    <div class="modal-body">
            {{{contents}}}
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-info" data-dismiss="modal" > Cancelar </button>
        {{^remove}}<button type="submit" class="btn btn-success" > Alterar </button>{{/remove}}
        {{#remove}}<button type="submit" class="btn btn-danger" > Remover </button>{{/remove}}
    </div>
</form>
{{/modal-form}}