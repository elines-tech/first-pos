<?php include '../restaurant/config.php'; ?>


<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3><?php echo $translations['Product Subcategory']?></h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../Dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $translations['Product Subcategory']?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
		<?php 
		if($insertRights==1){ ?>
			<div id="maindiv" class="container">
				<!--<div class="row">-->
					<!--<div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">-->
                    <div class="floating-action-button">
                        <a id="add_category" class="add_sub_category d-flex align-items-center justify-content-center">
                                <i class="fa fa-plus-circle cursor_pointer"></i>
                            </a>
                        </div>
					<!--</div>-->
				<!--</div>-->
			</div>
		<?php } ?>
        <!-- Basic Tables start -->
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                            <h5><?php echo $translations['Product Subcategory List']?></h5>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="print_div">
                    <table class="table table-striped" id="datatableProductSubCategory">
                        <thead>
                            <tr>
                                <th><?php echo $translations['Sr No']?></th>
                                <th><?php echo $translations['Subcategory Code']?></th>
                                <th><?php echo $translations['Category Name']?></th>
                                <th><?php echo $translations['Subcategory Name']?></th>
                                <!--<th>Short Name</th>-->
                                <th><?php echo $translations['Icon']?></th>
                                <th><?php echo $translations['Status']?></th>
                                <th><?php echo $translations['Action']?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>
</div>
</div>
</body>
<div class="modal fade text-left" id="generl_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel130" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id='modal_label'><?php echo $translations['Add Product Subcategory']?></h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body1">
                                <form id="subcategoryForm" class="form" data-parsley-validate>
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory">
                                                <label for="category-name-column" class="col-md-4 form-label text-left"><?php echo $translations['Category']?></label>
                                                <div class="col-md-8">
                                                    <!--<select class="form-select select2" style="width:100%" name="category" id="category" required data-parsley-required-message="Category is required">
                                                        <option value="">Select</option>
                                                        <?php
                                                        if ($categorydata) {
                                                            foreach ($categorydata->result() as $catData) {
                                                                //echo "<option value='" . $catData->code . "'>" . $catData->categoryName . "</option>'";
                                                            }
                                                        }
                                                        ?>
                                                    </select>-->
													<select class="form-select select2" style="width:100%" name="category" id="category"   required="" data-parsley-required-message="Category is required">
													
													</select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory" id="nameDiv">
                                                <label for="category-name-column" class="col-md-4 form-label text-left"><?php echo $translations['Name']?></label>
                                                <div class="col-md-8">
                                                    <input type="text" id="subcategoryName" class="form-control" placeholder="Enter SubCategory Name" name="subcategoryName" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12 d-none">
                                            <div class="form-group row mandatory" id="shortNameDiv">
                                                <label for="category-name-column" class="col-md-4 form-label text-left"><?php echo $translations['Short Name']?></label>
                                                <div class="col-md-8">
                                                    <input type="text" id="subcategorySName" class="form-control" maxlength="3" placeholder="Enter Short Name" name="subcategorySName">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row">
                                                <label for="category-name-column" class="col-md-4 form-label text-left"><?php echo $translations['Description']?></label>
                                                <div class="col-md-8">
                                                    <textarea id="description" rows="6" class="form-control" placeholder="" name="description"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12" id="file_uploadDiv">
                                            <div class="form-group row">
                                                <label for="category-name-column" class="col-md-4 form-label text-left"><?php echo $translations['Icon']?></label>
                                                <div class="col-md-8">
                                                    <input type="file" id="subcategoryIcon" class="form-control" name="subcategoryIcon">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row">
                                                <label for="status" class="col-sm-4 col-form-label text-left"><?php echo $translations['Active']?></label>
                                                <div class="col-sm-8 checkbox">
                                                    <input type="checkbox" name="isActive" id="isActive" class=" " style="width:25px; height:25px">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12 d-none" id="previewDiv">
                                            <div class="form-group row">
                                                <div class="col-sm-12 text-center">
                                                    <img src="" id="iconImg" height="300" width="300">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <input type="hidden" class="form-control" id="code" name="code">
                                            <input type="hidden" class="form-control" id="previousIcon" name="previousIcon">
											<?php if($insertRights==1){ ?>
												<button type="submit" class="btn btn-primary" id="saveSubCategoryBtn"><?php echo $translations['Save']?></button>
											<?php } ?>
                                            <button type="button" class="btn btn-light-secondary" id="closeSubCategoryBtn" data-bs-dismiss="modal"><?php echo $translations['Close']?></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.cancel').removeClass('btn-default').addClass('btn-info');
        loadCategory();
		$("#category").select2({
			    placeholder: "Select Category",
                allowClear: true,
			    dropdownParent: $('#generl_modal .modal-content'),
				ajax: {
					url:  base_path+'Common/getProductCategory',
					type: "get",
					delay:250,
					dataType: 'json',
					data: function (params) {
						var query = {
                            search: params.term
                          }
                          return query;
					}, 
					processResults: function (response) {
						return {
							results: response
						};
					},
					cache: true
				}	
		}); 
		
    });

    $('.add_sub_category').click(function() {
        $('#generl_modal').modal('show');
        $('#modal_label').text('Add Product Subcategory');
        $('#saveSubCategoryBtn').text('Save');
        $('#code').val('');
        $('#file_uploadDiv').removeClass('d-none');
        $('#saveSubCategoryBtn').removeClass('d-none');
        $('#subcategorySName').val('');
        $('#subcategoryName').val('');
        $('#subcategoryIcon').val('');
        $("#category").val(null).trigger('change.select2');
        $('#description').val('');
        $('#iconImg').attr('src', '');
        $('#previewDiv').addClass('d-none');
        $("#isActive").prop("checked", true);
    });

    function loadCategory() {
        if ($.fn.DataTable.isDataTable("#datatableProductSubCategory")) {
            $('#datatableProductSubCategory').DataTable().clear().destroy();
        }
        var dataTable = $('#datatableProductSubCategory').DataTable({
            stateSave: true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "searching": true,
            "ajax": {
                url: base_path + "ProductSubcategory/getSubCategoryList", 
                type: "GET",
                "complete": function(response) {
                    $('.edit_subcategory').click(function() {
                        var code = $(this).data('seq');
                        var type = $(this).data('type');
                        $.ajax({
                            url: base_path + "ProductSubcategory/editSubCategory",
                            type: 'POST',
                            data: {
                                'code': code,
                            },
                            success: function(response) {
                                var obj = JSON.parse(response);
                                if (obj.status) {
                                    $('#subcategoryForm').parsley().destroy();
                                    $('#generl_modal').modal('show');
                                    if (type == 1) {
                                        $('#modal_label').text('View Product Subcategory');
                                        $('#file_uploadDiv').addClass('d-none');
                                        $('#saveSubCategoryBtn').addClass('d-none');
                                    } else {
                                        $('#file_uploadDiv').removeClass('d-none');
                                        $('#saveSubCategoryBtn').removeClass('d-none');
                                        $('#modal_label').text('Update Product Subcategory');
                                        $('#saveSubCategoryBtn').text('Update');
                                    }

                                    $('#nameDiv').removeClass('is-invalid');
                                    $('#shortNameDiv').removeClass('is-invalid');
                                    $('#code').val(obj.code);
                                    $('#subcategoryName').val(obj.subcategoryName);
                                    $('#subcategorySName').val(obj.subcategorySName);
                                    $('#description').val(obj.description);
                                    $('select#category').empty();
                                    $('select#category').append(obj.categoryData);
                                    if (obj.icon != '') {
                                        $('#previewDiv').removeClass('d-none');
                                        $('#iconImg').attr('src', obj.icon);
                                        $('#previousIcon').val(obj.previousIcon);
                                    }
                                    if (obj.isActive == 1) {
                                        $("#isActive").prop("checked", true);
                                    }
                                } else {
                                    toastr('Something Wend Wrong', 'Edit Product Subcategory', {
                                        progressBar: true
                                    })
                                }
                            }
                        });
                    });
                    $('.delete_subcategory').on("click", function() {
                        var code = $(this).data('seq');
                        swal({
                            //title: "You want to delete category "+code,
                            title: "Are you sure you want to delete this?",
                            type: "warning",
                            showCancelButton: !0,
                            confirmButtonColor: "#DD6B55",
                            //cancelButtonColor: "#DD6B55",
                            confirmButtonText: "Yes, delete it!",
                            cancelButtonText: "No, cancel it!",
                            closeOnConfirm: !1,
                            closeOnCancel: !1
                        }, function(e) {
                            if (e) {
                                $.ajax({
                                    url: base_path + "ProductSubcategory/deleteSubCategory",
                                    type: 'POST',
                                    data: {
                                        'code': code
                                    },
                                    beforeSend: function() {

                                    },
                                    success: function(data) {
                                        swal.close();
                                        if (data) {
                                            toastr.success('Product Subcategory deleted successfully', 'Product Subcategory', {
                                                "progressBar": true
                                            });
                                            loadCategory();
                                        } else {
                                            toastr.error('Product Subcategory not deleted', 'Product Subcategory', {
                                                "progressBar": true
                                            });
                                        }
                                    }
                                });
                            } else {
                                swal.close();
                            }
                        });
                    });
                }
            }
        });
    }

    $("#subcategoryForm").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var form = $(this);
        form.parsley().validate();
        if (form.parsley().isValid()) {
            var isActive = 0;
            if ($("#isActive").is(':checked')) {
                isActive = 1;
            }
            formData.append('isActive', isActive);
            $.ajax({
                url: base_path + "ProductSubcategory/saveSubCategory",
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#saveSubCategoryBtn').prop('disabled', true);
                    $('#saveSubCategoryBtn').text('Please wait..');
                    $('#closeSubCategoryBtn').prop('disabled', true);
                },
                success: function(response) {
                    $('#saveSubCategoryBtn').prop('disabled', false);
                    if ($('#code').val() != '') {
                        $('#saveSubCategoryBtn').text('Update');
                    } else {
                        $('#saveSubCategoryBtn').text('Save');
                    }
                    $('#closeSubCategoryBtn').prop('disabled', false);
                    var obj = JSON.parse(response);
                    if (obj.status) {
                        toastr.success(obj.message, 'Product Subcategory', {
                            "progressBar": true
                        });
                        loadCategory()
                        if (code != '') {
                            $('#generl_modal').modal('hide');
                        } else {
                            $('#code').val('');
                            $('#subcategoryName').val('');
                            $('#subcategorySName').val('');
                            $('#description').val('');
                            $('#categoryIcon').val('');
                            $('#iconImg').attr('src', '');
                        }
                    } else {
                        toastr.error(obj.message, 'Product Subcategory', {
                            "progressBar": true
                        });
                    }
                }
            });
        }
    });
</script>