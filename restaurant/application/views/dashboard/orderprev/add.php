 <nav class="navbar navbar-light">
     <div class="container d-block">
         <div class="row">
             <div class="col-12 col-md-6 order-md-1 order-last"><a href="<?= base_url() ?>order/listRecords"><i class="fa fa-times fa-2x"></i></a></div>
         </div>
     </div>
 </nav>

 <div class="container">
     <section id="multiple-column-form" class="mt-5 catproduct">
         <div class="row match-height d-none" id="categoryDiv">
             <div class="col-12">
                 <div class="card">
                     <div class="card-content">
                         <div class="card-body">
                             <div class="row">
                                 <div class="col-md-9 col-12">
                                     <input type="hidden" class="form-control" id="urlTableCode" name="urlTableCode" value="<?= $tableCode ?>">
                                     <input type="hidden" class="form-control" id="tableCode" name="tableCode" value="">
                                     <input type="hidden" class="form-control" id="tableNumber" name="tableNumber" value="">
                                     <input type="hidden" class="form-control" id="tableCustPhone" name="tableCustPhone" value="">
                                     <input type="hidden" class="form-control" id="tableCustName" name="tableCustName" value="">
                                     <ul class="nav nav-tabs" role="tablist">
                                         <li class="nav-item" role="presentation">
                                             <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#allcat" role="tab" aria-controls="allcat" aria-selected="true">All categories</a>
                                         </li>
                                         <?php
                                            if ($categories) {
                                                foreach ($categories->result() as $cat) {
                                            ?>
                                                 <li class="nav-item" role="presentation">
                                                     <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#<?= $cat->code ?>" role="tab" aria-controls="<?= $cat->categoryName ?>" aria-selected="false"><?= $cat->categoryName ?></a>
                                                 </li>
                                         <?php
                                                }
                                            }
                                            ?>
                                     </ul>
                                     <div class="tab-content bg-light" id="myTabContent">
                                         <div class="container tab-pane fade show active" id="allcat" role="tabpanel" aria-labelledby="home-tab">
                                             <div class="ref row" style="position:relative; z-index:10;">
                                                 <?php
                                                    if (isset($products)) {
                                                        foreach ($products->result() as $prc1) { ?>
                                                         <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4 col-p-4 d-inblock">
                                                             <div class="panel panel-bd product-panel select_product productDetails" data-productcode="<?= $prc1->code ?>">
                                                                 <div class="panel-body"><img src="<?= base_url() . $prc1->productImage ?>" width="150" height="150" class="img-responsive" alt="<?= $prc1->productEngName ?>"></div>
                                                                 <div class="panel-footer"><span><?= $prc1->productEngName ?></span></div>
                                                             </div>
                                                         </div>
                                                 <?php }
                                                    } ?>
                                             </div>
                                         </div>
                                         <?php
                                            if ($categories) {
                                                foreach ($categories->result() as $cat1) {
                                            ?>
                                                 <div class="container tab-pane fade" id="<?= $cat1->code ?>" role="tabpanel" aria-labelledby="profile-tab">
                                                     <div class="ref row" style="position:relative; z-index:10;">
                                                         <?php
                                                            if (isset(${$cat1->code})) {
                                                                foreach (${$cat1->code}->result() as $prc) { ?>
                                                                 <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4 col-p-4 d-inblock">
                                                                     <div class="panel panel-bd product-panel select_product">
                                                                         <div class="panel-body"> <img src="<?= base_url() . $prc->productImage ?>" class="img-responsive" width="150" height="150" alt="<?= $prc->productEngName ?>"></div>
                                                                         <div class="panel-footer"><span><?= $prc->productEngName ?></span></div>
                                                                     </div>
                                                                 </div>
                                                         <?php }
                                                            } ?>
                                                     </div>
                                                 </div>
                                         <?php }
                                            } ?>
                                     </div>
                                 </div>
                                 <div class="col-md-3 col-12">
                                     <form action="#" class="form-vertical" id="onlineordersubmit" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">
                                         <div class="maincart" style="bottom:0;">
                                             <h3 id="myTab">Cart:<span style="float:right" id="cartTableText"></span></h3>
                                             <div class="cart" id="cartDiv">
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
     </section>
 </div>

 <div class="modal fade text-left" id="bookTableModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" aria-modal="true" style="display: none;">
     <div class="modal-dialog modal-md">
         <div class="modal-content">
             <div class="modal-header">
                 <h3>Book Table First</h3>
                 <button type="button" class="close" data-bs-dismiss="modal">×</button>
             </div>
             <div class="modal-body">
                 <form class="extablk" method="post">
                     <div class="row">
                         <div class="col-md-12 mb-2">
                             <label for="modaltableCode">Select Table</label>
                             <select id="modaltableCode" name="modaltableCode" class="form-control">
                                 <option value="">Select</option>
                                 <?php
                                    $optgroup = "";
                                    if ($tableData) {
                                        $option = [];
                                        $sections = [];
                                        foreach ($tableData->result() as $tbl) {
                                            if (!in_array($tbl->tableSection, $sections)) array_push($sections, $tbl->tableSection);
                                        }
                                        foreach ($sections as $section) {
                                            $html = "<optgroup label='Section $section'>";
                                            foreach ($tableData->result() as $tbl) {
                                                if ($tbl->tableSection == $section) {
                                                    $html .= "<option value='" . $tbl->code . "'>" . $tbl->tableNumber . "</option>";
                                                }
                                            }
                                            $html .= "</optgroup>";
                                            $optgroup .= $html;
                                        }
                                    }
                                    echo $optgroup;
                                    ?>
                             </select>
                         </div>
                         <div class="col-md-12 mb-2">
                             <label for="modaltablePhoneNo">Enter Phone Number</label>
                             <input class="form-control" id="modaltablePhoneNo" name="modaltablePhoneNo" />
                         </div>
                         <div class="col-md-12 mb-2">
                             <label for="modaltableName">Enter Name (Optional)</label>
                             <input class="form-control" id="modaltableName" name="modaltableName" />
                         </div>
                     </div>
                 </form>
             </div>
             <div class="modal-footer">
                 <button type="button" id="bookTableBtn" class="btn btn-sm btn-success">Book Table</button>
             </div>
         </div>
         <div class="modal-footer"> </div>
     </div>
 </div>

 <div class="modal fade text-left" id="edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" aria-modal="true" style="display: none;">
     <div class="modal-dialog modal-md">
         <div class="modal-content">
             <div class="modal-header" style="align-items: center;">
                 <button type="button" class="close" data-bs-dismiss="modal">×</button>
                 <h3>Add product</h3>
             </div>
             <div class="modal-body addonsinfo">
                 <form id="addToCart" class="extablk" method="post">
                     <table class="table table-bordered table-hover bg-white" id="productTable">
                         <thead>
                             <tr>
                                 <th class="text-center">Product</th>
                                 <th class="text-center">Varients</th>
                                 <th class="text-center wp_100">Quantity</th>
                                 <th class="text-center wp_120">Price</th>
                             </tr>
                         </thead>
                         <tbody id="addItem">

                         </tbody>
                     </table>
                     <table class="table table-bordered table-hover bg-white" id="addonTable">
                         <thead>
                             <tr>
                                 <th class="text-center"></th>
                                 <th class="text-center">Extras Name</th>
                                 <th class="text-center wp_100">Quantity</th>
                                 <th class="text-center">Price </th>
                             </tr>
                         </thead>
                         <tbody>

                         </tbody>
                     </table>
                     <a class="btn btn-success asingle sub_1" id="add_to_cart">Add To cart</a>
                 </form>
             </div>
         </div>
         <div class="modal-footer"> </div>
     </div>
 </div>

 <script>
     $(document).ready(function() {
         if ($('#modaltableCode').val() == '' && $('#urlTableCode').val() == '') {
             $('#bookTableModal').modal("show");
         } else {
             $('#tableCode').val($('#urlTableCode').val())
             $('#categoryDiv').removeClass('d-none');
             getCartDetails();
         }
     });
     $('#bookTableBtn').click(function() {
         var tableCode = $('#modaltableCode').val();
         var tableNumber = $('#modaltableCode').find(":selected").text();
         var tableCustomerPhone = $("#modaltablePhoneNo").val().trim();
         var tableCustomerName = $("#modaltableName").val().trim();
         if (tableCode != '') {
             $('#bookTableModal').modal('hide');
             $('#categoryDiv').removeClass('d-none');
             $('#tableCode').val(tableCode);
             $('#tableNumber').val(tableNumber);
             $('#cartTableText').text(tableNumber);

             $('#tableCustPhone').text(tableCustomerPhone);
             $('#tableCustName').text(tableCustomerName);

             getCartDetails();
         } else {
             $('#categoryDiv').addClass('d-none');
         }
     });
     $("body").on("click", ".deleteCartProduct", function(e) {
         debugger;
         var cartCode = $(this).data('seq');
         if (cartCode != '') {
             $.ajax({
                 url: base_path + "order/deleteCartProduct",
                 type: 'POST',
                 data: {
                     'cartCode': cartCode,
                 },
                 success: function(response) {
                     var obj = JSON.parse(response);
                     if (obj.status) {
                         $('#' + cartCode).remove();
                     }
                 }
             });
         }
     });
     $('.productDetails').click(function() {
         var productCode = $(this).attr('data-productcode');
         if (productCode != '') {
             $.ajax({
                 url: base_path + "order/getproductDetails",
                 type: 'POST',
                 data: {
                     'productCode': productCode,
                 },
                 success: function(response) {
                     var obj = JSON.parse(response);
                     if (obj.status) {
                         $('#edit').modal('show');
                         if (obj.addonHtml != '') {
                             $('#addonTable').removeClass('d-none')
                             tableBody = $("#addonTable tbody");
                             tableBody.html('');
                             tableBody.append(obj.addonHtml);
                         } else {
                             $('#addonTable').addClass('d-none')
                         }
                         tableBody1 = $("#productTable tbody");
                         tableBody1.html('');
                         tableBody1.append(obj.varientHtml);
                     } else {
                         toastr.error('Failed to get product details', 'Product Details', {
                             progressBar: true
                         })
                     }
                 }
             });
         } else {
             toastr('Something went wrong', 'Product Details', {
                 progressBar: true
             })
         }
     });
     $('#add_to_cart').click(function() {
         var tableCode = $('#tableCode').val();
         var productCode = $('#productCode').val();
         var variantCode = $('#variantCode').val()
         var productQty = $('#productQty').val();
         var productPrice = $('#productPrice').val();
         var table = document.getElementById("addonTable");
         var table_len = (table.rows.length) - 1;
         var tr = table.getElementsByTagName("tr");
         var addOnArray = [];
         var totalPrice = productPrice;
         for (i = 1; i <= table_len; i++) {
             var addon = [];
             var id = tr[i].id.substring(3);
             var addonCode = document.getElementById("addonCode" + id).value;
             var addonName = document.getElementById("addonName" + id).value;
             var addonQty = document.getElementById("addonQty" + id).value;
             var addonPrice = document.getElementById("addonPrice" + id).value;
             totalPrice = Number(totalPrice) + Number(addonPrice);
             addon.push(addonCode, addonName, addonQty, addonPrice);
             addOnArray.push(addon);
         }
         var addOns = JSON.stringify(addOnArray);
         $.ajax({
             url: base_path + "order/addToCart",
             type: 'POST',
             data: {
                 'variantCode': variantCode,
                 'productCode': productCode,
                 'productQty': productQty,
                 'productPrice': productPrice,
                 'addOns': addOns,
                 'totalPrice': totalPrice,
                 'tableCode': tableCode
             },
             beforeSend: function() {
                 $('#add_to_cart').prop('disabled', true);
                 $('#addToCart').html('<i class="fa fa-spinner"></i>');
             },
             success: function(response) {
                 $('#add_to_cart').prop('disabled', false);
                 $('#addToCart').text('Add To cart');
                 var obj = JSON.parse(response);
                 if (obj.status) {
                     getCartDetails();
                     toastr.success(obj.message, 'Add to Cart', {
                         progressBar: true
                     })
                 } else {
                     toastr.error(obj.message, 'Add to Cart', {
                         progressBar: true
                     })
                 }

             }
         });
     });

     function getCartDetails() {
         var tableCode = $('#tableCode').val();
         var tableNumber = $('#tableNumber').val();
         $.ajax({
             url: base_path + "order/getCartDetails",
             type: 'POST',
             data: {
                 'tableCode': tableCode,
                 'tableNumber': tableNumber
             },
             success: function(response) {
                 var obj = JSON.parse(response)
                 $('#cartDiv').html('');
                 $('#cartDiv').append(obj.cartHtml);
             }
         });
     }

     function calculateAddonTotal(id) {
         var addonQty = $('#addonQty' + id).val();
         var previous = $('#oldaddonPrice' + id).val()
         var addonSubTotal = $('#addonSubTotal' + id).text();
         subTotal = Number(previous) * Number(addonQty);
         $('#addonPrice' + id).val(Number(subTotal).toFixed(2));
         $('#addonSubTotal' + id).text(Number(subTotal).toFixed(2));
     }

     function calculateProductTotal() {
         var productQty = $('#productQty').val();
         var previous = $('#oldproductPrice').val()
         subTotal = Number(previous) * Number(productQty);
         $('#productPrice').val(Number(subTotal).toFixed(2));
         $('#productPriceTotal').text(Number(subTotal).toFixed(2));
     }

     function updateCartPrices(type1, productCode) {
         $.ajax({
             url: base_path + "order/updateCartPrices",
             type: 'POST',
             data: {
                 'type': type1,
                 'productCode': productCode
             },
             success: function(response) {
                 getCartDetails();
             }
         });
     }
 </script>