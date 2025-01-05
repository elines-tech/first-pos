 <div id="main-content">
     <div class="page-heading">
         <div class="page-title">
             <div class="row">
                 <div class="col-12 col-md-6 order-md-1 order-last">
                     <h3>Gift Sell Detail</h3>
                 </div>
                 <div class="col-12 col-md-6 order-md-2 order-first">
                     <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                         <ol class="breadcrumb">
                             <li class="breadcrumb-item"><a href="../../dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                             <li class="breadcrumb-item active" aria-current="page">Gift Sell Detail</li>
                         </ol>
                     </nav>
                 </div>
             </div>
         </div>
         <?php
            if ($giftSell) {
                $giftSell = $giftSell->result_array()[0];
                $giftCard = json_decode($giftSell['cardDetails'], true);
            ?>
             <section class="section">
                 <div class="card">
                     <div class="card-header">
                         <div class="row">
                             <div class="col-12 col-md-6">
                                 <h5>Gift Sell Card Details</h5>
                             </div>
                             <div class="col-12 col-md-6 text-end">
                                 <a id="cancelDefaultButton" href="<?= base_url('giftcardReport/list') ?>" class="btn btn-sm btn-info">Back</a>
                             </div>
                         </div>
                         <div class="row mt-3">
                             <div class="col-md-4">
                                 <label class="form-label lng">Name</label>
                                 <div class="form-group mandatory">
                                     <input type="text" class="form-control-line" id="name" value="<?= $giftSell['custName'] ?>">
                                 </div>
                             </div>
                             <div class="col-md-4">
                                 <label class="form-label lng">Phone</label>
                                 <div class="form-group mandatory">
                                     <input type="text" class="form-control-line" id="phone" value="<?= $giftSell['custPhone'] ?>">
                                 </div>
                             </div>
                             <div class="col-md-4">
                                 <label class="form-label lng">Phone</label>
                                 <div class="form-group mandatory">
                                     <input type="text" class="form-control-line" id="email" value="<?= $giftSell['custEmail'] ?>">
                                 </div>
                             </div>
                             <div class="col-md-3">
                                 <label class="form-label lng">Sell Date</label>
                                 <div class="form-group mandatory">
                                     <input type="text" class="form-control-line" id="sellDate" value="<?= date('d/M/y', strtotime($giftSell['addDate'])) ?>">
                                 </div>
                             </div>
                             <div class="col-md-3">
                                 <label class="form-label lng">Discount (%) <small>Per card</small></label>
                                 <div class="form-group mandatory">
                                     <input type="text" class="form-control-line" id="toDate" name="expDate" value="<?= $giftCard['discount'] ?>">
                                 </div>
                             </div>
                             <div class="col-md-3">
                                 <label class="form-label lng">Price <small>Per card</small></label>
                                 <div class="form-group mandatory">
                                     <input type="text" class="form-control-line" id="toDate" name="expDate" value="<?= $giftCard['price'] ?>">
                                 </div>
                             </div>
                             <div class="col-md-3">
                                 <label class="form-label lng">Validity (Days)</label>
                                 <div class="form-group mandatory">
                                     <input type="text" class="form-control-line" id="toDate" name="expDate" value="<?= $giftCard['validityInDays'] ?>">
                                 </div>
                             </div>

                             <div class="col-md-3">
                                 <label class="form-label lng">Expiry Date</label>
                                 <div class="form-group mandatory">
                                     <input type="text" class="form-control-line" id="toDate" name="expDate" value="<?= date('d/M/y', strtotime($giftSell['expiryDate'])) ?>">
                                 </div>
                             </div>
                             <div class="col-md-3">
                                 <label class="form-label lng">Issued Cards</label>
                                 <div class="form-group mandatory">
                                     <input type="text" class="form-control-line" id="issuedCards" value="<?= $giftSell['cardCount'] ?>">
                                 </div>
                             </div>
                             <div class="col-md-3">
                                 <label class="form-label lng">Total Price</label>
                                 <div class="form-group mandatory">
                                     <input type="text" class="form-control-line" id="totalPrice" value="<?= $giftSell['totalPrice'] ?>">
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="card-body">
                         <table class="table table-striped">
                             <thead>
                                 <tr>
                                     <th>Card No.</th>
                                     <th>Name</th>
                                     <th>Phone</th>
                                     <th>Email</th>
                                 </tr>
                             </thead>
                             <tbody>
                                 <?php
                                    if ($giftSellLine) {
                                        foreach ($giftSellLine->result() as $r) {
                                    ?>
                                         <tr>
                                             <td><?= $r->giftNo ?></td>
                                             <td><?= $r->custName ?></td>
                                             <td><?= $r->custPhone ?></td>
                                             <td><?= $r->custEmail ?></td>
                                         </tr>
                                 <?php
                                        }
                                    }
                                    ?>
                             </tbody>
                         </table>
                     </div>
                 </div>
             </section>
         <?php
            }
            ?>
     </div>
 </div>