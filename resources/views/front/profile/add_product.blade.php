<div class="modal modal-add-product fade bd-add-product-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add products</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="mt-1" action="{{ route('products.create') }}">
                    <div class="form-row">
                        <div class="col">
                            <label for="staticEmail" class="col-form-label">Name</label>
                            <input type="text" class="form-control" name="name" placeholder="name">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <label for="description" class="col-form-label">Description</label>
                            <input type="text" id="description" class="form-control" name="description" placeholder="description">
                        </div>
                        <div class="col">
                            <label for="price" class="col-form-label">Price</label>
                            <input type="text" id="price" class="form-control" name="price" placeholder="price">
                        </div>
                    </div>
                    <div class="form-row mt-2">
                        <div class="col">
                            <button class="btn btn-primary text-right" type="submit">Create</button>
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