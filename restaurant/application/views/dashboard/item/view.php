<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Item</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Item</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row mb-2 float-right">
                <div class="col-12 col-md-12">
                    <a href="<?= base_url() ?>item/listRecords" class="btn btn-primary text-center">back</a>
                    <button class="btn btn-primary text-center edit_item">Edit Item</button>
                </div>
            </div>
        </div>
        <div class="row">
            <section class="section col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                                <h5>Item</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        if ($itemData) {
                            foreach ($itemData->result() as $br) {
                        ?>
                                <div class="row mb-1">
                                    <form class="form">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="itemName">Item Name : </label>
                                                <input type="hidden" class="form-control-line" name="itemCode" id="itemCode" value="<?= $br->code ?>">
                                                <input type="text" class="form-control-line" id="itemName" value="<?= $br->itemEngName ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="itemUnit">Storage Unit : </label>
                                                <input type="text" class="form-control-line" id="itemUnit" value="<?= $br->unitName ?>">
                                            </div>

                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="ingredientUnit">Ingredient Unit: </label>
                                                <input type="text" class="form-control-line" id="ingredientUnit" value="<?= $br->ingUnitName ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="ingredientFactor">Ingredient Factor : </label>
                                                <input type="text" class="form-control-line" id="ingredientFactor" value="<?= $br->ingredientFactor ?>">
                                            </div>

                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <label for="itemPrice">Item price : </label>
                                                <input type="text" class="form-control-line" id="itemPrice" value="<?= $br->itemPrice ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="itemArbName">Item Arablc Name : </label>
                                                <input type="text" class="form-control-line" id="itemArbName" value="<?= $br->itemArbName ?>">
                                            </div>

                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <label for="itemHinName">Item Hindi Name : </label>
                                                <input type="text" class="form-control-line" id="itemHinName" value="<?= $br->itemHinName ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="itemUrduName">Item Urdu Name : </label>
                                                <input type="text" class="form-control-line" id="itemUrduName" value="<?= $br->itemUrduName ?>">
                                            </div>

                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <label for="itemDesc">Item description : </label>
                                                <textarea rows="4" type="text" class="form-control" id="itemDesc"><?= $br->itemEngDesc ?></textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="itemDesc">Item Arabic description : </label>
                                                <textarea type="text" rows="4" class="form-control" id="itemArbDesc"><?= $br->itemArbDesc ?></textarea>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <label for="itemHinDesc">Item Hindi description : </label>
                                                <textarea type="text" rows="4" class="form-control" id="itemHinDesc"><?= $br->itemHinDesc ?></textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="itemUrduDesc">Item Urdu description : </label>
                                                <textarea type="text" rows="4" class="form-control" id="itemUrduDesc"><?= $br->itemUrduDesc ?></textarea>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                        <?php }
                        } ?>
                    </div>
                </div>
            </section>

        </div>
        <!-- Basic Tables end -->
    </div>
</div>
</div>
</div>
</body>
<div class="modal fade text-left" id="generl_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel130" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Update Item</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body1">

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
    $('.edit_item').click(function() {
        var itemCode = $('#itemCode').val();
        $.ajax({
            url: base_path + "item/edit",
            type: 'POST',
            data: {
                'code': itemCode,
            },
            success: function(response) {
                $('#generl_modal').modal('show');
                $(".panel-body1").html(response);
            }
        });
    });
    $(document).on("click", "button#updateItemBtn", function(e) {
        $('#updateitemForm').parsley();
        const form = document.getElementById('updateitemForm');
        var formData = new FormData(form);
        var isValid = true;
        e.preventDefault();
        $("#updateitemForm .form-control").each(function(e) {
            if ($(this).parsley().validate() !== true) isValid = false;
        });
        $("#updateitemForm .form-select").each(function(e) {
            if ($(this).parsley().validate() !== true) isValid = false;
        });
        if (isValid) {
            var isActive = 0;
            if ($("#modalisActive").is(':checked')) {
                isActive = 1;
            }
            formData.append('modalisActive', isActive);
            $.ajax({
                url: base_path + "item/updateItem",
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#updateItemBtn').prop('disabled', true);
                    $('#updateItemBtn').text('Please wait..');
                    $('#closeItemBtn').prop('disabled', true);
                },
                success: function(response) {
                    $('#updateItemBtn').prop('disabled', false);
                    $('#updateItemBtn').text('Update');
                    $('#closeItemBtn').prop('disabled', false);
                    var obj = JSON.parse(response);
                    if (obj.status) {
                        toastr.success(obj.message, 'Item', {
                            "progressBar": true
                        });
                        location.reload();
                        $('#generl_modal').modal('hide');
                        $(".panel-body1").html('');
                    } else {
                        toastr.error(obj.message, 'Item', {
                            "progressBar": true
                        });
                    }
                }
            });
        }
    });
</script>