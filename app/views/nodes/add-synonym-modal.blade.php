<div class="modal fade" id="addSynonymModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="addNodeModalLabel">Add Synonym Word for <strong>{{ $node->getProperty("word") }}</strong></h4>
            </div> 

            <div class="modal-body">
                <form method="POST" action="/nodes/secure/add-synonym" class="form col-md-12 center-block">
                    {{ Form::token() }}
                    <input type="hidden" name="word1" value="{{{$node->getProperty("word")}}}" />
                    <input type="hidden" name="lang" value="{{{$node->getProperty("lang")}}}" />

                    <div class="form-group col-md-12">
                        <input type="text" placeholder="Related Word" name="word2"  
                               class="form-control input-lg" />
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="controls">
                            <button type="submit" class="btn btn-success btn-lg">Add Synonym</button>
                        </div>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> 
            </div>
        </div>
    </div>
</div>