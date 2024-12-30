<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-7 col-md-7">
                    <h3>Subscriber Detail</h3>
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../../dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Subscribers</li>
                            <li class="breadcrumb-item active" aria-current="page">View</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-5 col-md-5 align-items-center text-end">
                    <a href="<?= base_url('/client/listRecords') ?>" class="btn btn-sm btn-primary">Back</a>
                </div>
            </div>
        </div>
    </div>
    <section class="section">
        <?php
        $buildingNo = $streetName = $district = $city = $postalCode = $country = "";
        if ($clients) {
            foreach ($clients->result() as $client) {
                if ($client->address != null && $client->address != "") {
                    $address = json_decode($client->address);
                    $buildingNo = $address->buildingNo;
                    $streetName = $address->streetName;
                    $district = $address->district;
                    $city = $address->city;
                    $postalCode = $address->postalCode;
                    $country = $address->country == "966" ? "Saudi Arabia" : "United Arab Emirates";
                }
        ?>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Subscriber Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <p>Personal Details : </p>
                                    <hr>
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <label for="tax">Registration Date</label>
                                    <input type="text" readonly class="form-control" readonly value="<?= date('d/m/Y h:i A', strtotime($client->registerDate)) ?>" />
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <label for="title">Name</label>
                                    <input type="text" readonly class="form-control" readonly value="<?= $client->name ?>" />
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <label for="freetrialdays">Email</label>
                                    <input type="text" readonly class="form-control" readonly value="<?= $client->email ?>" />
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <label for="tax">Phone</label>
                                    <input type="text" readonly class="form-control" readonly value="<?= $client->phone ?>" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <p>Company Details : </p>
                                    <hr>
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <label for="type">Company Code</label>
                                    <input type="text" readonly class="form-control" readonly value="<?= $client->cmpcode ?>" />
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <label for="type">Company Name</label>
                                    <input type="text" readonly class="form-control" readonly value="<?= $client->companyname ?>" />
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <label for="tax">VAT Number</label>
                                    <input type="text" readonly class="form-control" readonly value="<?= $client->vatno ?>" />
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <label for="tax">CR Number</label>
                                    <input type="text" readonly class="form-control" readonly value="<?= $client->crno ?>" />
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <label for="tax">On-Boarding Complete?</label>
                                    <input type="text" readonly class="form-control" readonly value="<?= $client->onBoard == 0 ? "Complete" : "In-Complete" ?>" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <p>Address : </p>
                                    <hr>
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <label for="type">Building No</label>
                                    <input type="text" readonly class="form-control" readonly value="<?= $buildingNo ?>" />
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <label for="type">Street Name</label>
                                    <input type="text" readonly class="form-control" readonly value="<?= $streetName ?>" />
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <label for="tax">District</label>
                                    <input type="text" readonly class="form-control" readonly value="<?= $district ?>" />
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <label for="tax">City</label>
                                    <input type="text" readonly class="form-control" readonly value="<?= $city ?>" />
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <label for="tax">Country</label>
                                    <input type="text" readonly class="form-control" readonly value="<?= $country ?>" />
                                </div>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <label for="tax">Postal Code</label>
                                    <input type="text" readonly class="form-control" readonly value="<?= $postalCode ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        <?php
            }
        } ?>
    </section>
</div>