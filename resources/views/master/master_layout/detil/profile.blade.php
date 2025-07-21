<div class="page-heading">
    <div class="card">
        <div class="card-header">
            <h4>Profile LayOut</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-4">
                    <label for="">Name</label>
                    <input type="text" class ="form-control" id="layoutName" value="{{$layout->name ?? ''}}">
                    <input type="hidden" class ="form-control" id="layoutId" value="{{$layout->id}}">
                </div>
                <div class="col-4">
                    <label for="">Remarks</label>
                    <textarea name="" class="form-control" id="layoutRemark" cols="10" rows="4">{{$layout->remark ?? ''}}</textarea>
                </div>
                <div class="col-4">
                    <label for="">Last Update</label>
                    <div class="input-group">
                        <input type="datetime-local" name="" id="" class="form-control" value="{{$layout->updated_at}}" disabled>
                        <div class="mb-1">
                            <button type="button" id="" onClick="updateProfileLayout(this)" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
                <div class="col-2 d-flex flex-column justify-content-center">
                </div>
            </div>
        </div>
    </div>
</div>