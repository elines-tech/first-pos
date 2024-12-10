<nav class="navbar navbar-light">
    <div class="container d-block">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last"><a href="transfer.php"><i class="fa fa-times fa-2x"></i></a></div>

            <div class="col-12 col-md-6 order-md-1 order-first">
                <button type="submit" class="btn btn-success white mr-2 lng float-lg-end sub_1" id="sub_1" key="save_close"> Save </button>
            </div>
        </div>
    </div>
</nav>


<div class="container">

    <!-- // Basic multiple Column Form section start -->
    <section id="multiple-column-form" class="mt-5">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Update Transfer</h3>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form" data-parsley-validate>
                                <div class="form-group row">


                                    <div class="col-md-3 col-sm-12 col-xs-12 mb-3">
                                        <label class="form-label lng">Transfer #<i class="text-danger">*</i></label>
                                        <input type="text" name="text" class="form-control bg-white">
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xs-12 mb-3">
                                        <label class="form-label lng">Date<i class="text-danger">*</i></label>
                                        <input type="date" name="req_date" class="form-control bg-white">
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xs-12 mb-3">
                                        <label class="form-label lng">Branch Name from</label>
                                        <div class="form-group mb-3 d-flex">
                                            <div class="col-md-10 col-sm-12 col-xs-12">
                                                <select class="choices form-control">
                                                    <option value="square">Pro1</option>
                                                    <option value="rectangle">Pro2</option>

                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xs-12 mb-3">
                                        <label class="form-label lng">Branch Name To</label>
                                        <div class="form-group mb-3 d-flex">
                                            <div class="col-md-10 col-sm-12 col-xs-12">
                                                <select class="choices form-control">
                                                    <option value="square">Pro1</option>
                                                    <option value="rectangle">Pro2</option>

                                                </select>
                                            </div>

                                            <a href="branch-add.php" class="col-md-2 col-sm-12 col-xs-12 circleico btn btn-primary a_plus text-light d-flex align-items-center justify-content-center rounded-circle shadow-sm " style="">
                                                <i class="fa fa-plus"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                        <label class="form-label lng">Item Name<i class="text-danger">*</i></label>
                                        <select class="choices form-control">
                                            <option value="square">Pro1</option>
                                            <option value="rectangle">Pro2</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                        <label class="form-label lng">Variant Name<i class="text-danger">*</i></label>
                                        <select class="choices form-control">
                                            <option value="square">Pro1</option>
                                            <option value="rectangle">Pro2</option>
                                        </select>
                                    </div>


                                </div>

                                <div class="col-md-12 ">
                                    <div class="btn-group mb-3">
                                        <button type="button" class="btn btn-outline-primary btn-sm waves-effect waves-light p-2"><i class="fa fa-plus"></i> <span class="lng">Product</span></button>
                                    </div>

                                </div>
                                <table class="table table-bordered table-hover" id="purchaseTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center">S.No.</th>
                                            <th class="text-center">Item Information<i class="text-danger">*</i></th>

                                            <th class="text-center">Price<i class="text-danger">*</i></th>
                                            <th class="text-center">Qty<i class="text-danger">*</i></th>
                                            <th class="text-center">UOM</th>
                                            <th class="text-center">Sub Total</th>
                                            <th class="text-center">Tax Rate%</th>
                                            <th class="text-center">Tax Amount</th>
                                            <th class="text-center">Net Total</th>
                                            <th class="text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="addPurchaseItem">
                                        <tr>
                                            <td class="num">1</td>
                                            <td class="span3 supplier">
                                                <!--<input type="text" name="product_name" required="" class="form-control product_name" onkeypress="product_list(1);" placeholder="Item Name" id="product_name_1" tabindex="5">
                                   <input type="hidden" class="autocomplete_hidden_value product_id_1" name="product_id[]" id="SchoolHiddenId">
                                   <input type="hidden" class="sl" value="1">-->
                                                <select class="choices form-control">
                                                    <option value="square">Select Ingredient</option>
                                                    <option value="rectangle">Pro2</option>
                                                </select>
                                            </td>

                                            <td class="test">
                                                <input type="number" name="product_rate" id="product_rate_1" class="form-control product_rate_1 text-right" placeholder="0.00" value="" min="0" tabindex="7" autocomplete="off">
                                            </td>

                                            <td class="text-right">
                                                <input type="number" name="product_quantity" id="cartoon_1" class="form-control text-right store_cal_1" placeholder="0.00" value="" min="0" tabindex="6" autocomplete="off">
                                            </td>
                                            <td class="text-right">
                                                <input type="text" name="product_uom" id="cartoon_uom" class="form-control text-right store_cal_uom" placeholder="" value="" disabled="">
                                            </td>
                                            <td class="text-right">
                                                <input type="number" name="product_subt" id="cartoon_subto" class="form-control text-right store_cal_subt" placeholder="0.00" value="" disabled="">
                                            </td>
                                            <td class="text-right">
                                                <input type="number" name="product_tax" id="cartoon_tax" class="form-control text-right store_cal_tax" placeholder="0.00" value="" disabled="">
                                            </td>
                                            <td class="text-right">
                                                <input type="number" name="product_taxam" id="cartoon_taxam" class="form-control text-right store_cal_taxam" placeholder="" value="" disabled="">
                                            </td>


                                            <td class="text-right">
                                                <input class="form-control total_price text-right" type="text" name="total_price" id="total_price_1" value="0.00" readonly="readonly" autocomplete="off">
                                            </td>
                                            <td>

                                                <i id="add" title="Add" class="fa fa-plus text-info cursor_pointer mx-2"></i><br>
                                                <i id="delete" title="Delete" class="fa fa-trash text-danger mx-2 cursor_pointer"></i>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>

                                            <td colspan="9" class="text-right"><b>Total :</b></td>
                                            <td class="text-right">
                                                <input type="text" id="total" class="text-right form-control" name="total" value="0.00" readonly="readonly" autocomplete="off">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="9" class="text-right"><b>Discount :</b></td>
                                            <td class="text-right">
                                                <input type="text" id="disc" class="text-right form-control" name="disc" placeholder="0.00" autocomplete="off">
                                            </td>
                                        </tr>
                                        <tr>

                                            <td colspan="9" class="text-right"><b>Discount Amount :</b></td>
                                            <td class="text-right">
                                                <input type="text" id="disc_amt" class="text-right form-control" name="disc_amt" value="0.00" readonly="readonly" autocomplete="off">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="9" class="text-right"><b>Tax :</b></td>
                                            <td class="text-right">
                                                <input type="text" id="tax" class="text-right form-control" name="tax" placeholder="0.00" autocomplete="off">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="9" class="text-right"><b>Shipping Charges :</b></td>
                                            <td class="text-right">
                                                <input type="text" id="ship" class="text-right form-control" name="ship" placeholder="0.00" autocomplete="off">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="9" class="text-right"><b>Grand Total :</b></td>
                                            <td class="text-right">
                                                <input type="text" id="grandtotal" class="text-right form-control" name="grandtotal" placeholder="0.00" autocomplete="off" readonly="readonly">
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-success white me-2 mb-1 sub_1" id="sub_1">Save</button>
                                        <button type="reset" class="btn btn-light-secondary me-1 mb-1" id="sub_1">Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- // Basic multiple Column Form section end -->
</div>