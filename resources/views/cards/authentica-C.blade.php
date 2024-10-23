
<div class="mx-auto sm:px-6 lg:px-8" id="SMtypeC">
    <div class="row">
        <div class="col-2">
            <div class="card h-100">
                <div class="card-header bg-light-success text-success text-uppercase d-flex flex-row">
                    <i class="bi bi-credit-card"></i>
                    <span id="cardid" class="my-auto ms-2" >ID:1234567890123456</span>
                </div>
                <div class="card-body">
                    <img src="images/type C.png" alt="Wibio smartcard type-C" class="mx-auto my-auto">
                </div>
                <div class="card-footer text-muted">Card state: <span id="cardstate">present</span></div>
            </div>
        </div>
        <div class="col-8">
            <div class="card h-100">
                <div class="card-header bg-light-success text-success text-uppercase d-flex flex-row">
                    <i class="bi bi-info-circle"></i>
                    <span class="my-auto ms-2" ><b>Type:</b> C</span>
                </div>
                <div class="card-body text-uppercase" style="position: relative;">
                    <p class="my-4"><b>Name:</b> <span id="cardusername">John Doe</span></p>
                    <p class="my-4"><b>Expire date:</b> <span id="cardexpire">2023-12-31</span></p>
                    <p class="my-4"><b>Email:</b> <span id="cardemail">jd@mail.com</span></p>
                </div>
            </div>
        </div>
        <div class="col-2">
            <div class="d-flex flex-row my-2">
                <p class="my-auto mx-auto d-inline-block">
                    <i id="batterylevel_icon" class="bi bi-battery-half"></i>
                    <b id="batterylevel_text" class="txt-battery-half">low</b>
                </p>
            </div>
            <div class="d-block h-75">
                <button class="my-2 btn btn-success w-100">Enroll fingerprints</button>
                <button class="my-2 btn btn-secondary w-100">Print card data</button>
                <button class="my-2 btn btn-primary w-100">Card management</button> <!-- Associate card to user -->
            </div>
        </div>
        <div class="mt-2 col-6">
            <div class="card h-100">
                <div class="card-header bg-light-success text-success text-uppercase d-flex flex-row">
                    <i class="bi bi-fingerprint"></i>
                    <span class="my-auto ms-2" >Otp sequences</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <select class="form-select" size="10" aria-label="size 3 select example">
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                                <option value="4">Four</option>
                                <option value="5">Five</option>
                                <option value="6">Six</option>
                                <option value="7">Seven</option>
                                <option value="8">Eight</option>
                                <option value="9">Nine</option>
                                <option value="10">Ten</option>
                            </select>
                            <button class="my-2 btn btn-success w-100" id ="getOtp">Get OTP</button>
                        </div>
                        <div class="col-4 sensor_container">
                            <img src="images/fingerprint_partial.png" id="imgfinger" class="mx-auto" alt="Wibio fingerprint request">
                        </div>
                    </div>
                </div>
                <div class="card-footer text-muted">Select a sequence and click on button to retrive OTP from smartcard</div>
            </div>
        </div>
        <div class="mt-2 col-6">
            <div class="card h-100">
                <div class="card-header bg-light-success text-success text-uppercase d-flex flex-row">
                    <i class="bi bi-database-gear"></i>
                    <span class="my-auto ms-2" >Card data</span>
                </div>
                <div class="card-body">
                    <div class="col">
                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                Accordion Item #1
                                </button>
                            </h2>
                            <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the first item's accordion body.</div>
                            </div>
                            </div>
                            <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                Accordion Item #2
                                </button>
                            </h2>
                            <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the second item's accordion body. Let's imagine this being filled with some actual content.</div>
                            </div>
                            </div>
                            <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                                Accordion Item #3
                                </button>
                            </h2>
                            <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the third item's accordion body. Nothing more exciting happening here in terms of content, but just filling up the space to make it look, at least at first glance, a bit more representative of how this would look in a real-world application.</div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="my-2 col-4">
            <div class="card h-100">
                <div class="card-header bg-light-success text-success text-uppercase d-flex flex-row">
                    <i class="bi bi-gear"></i>
                    <span class="my-auto ms-2" >Settings</span>
                </div>
                <div class="card-body">
                    <div class="form mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Connection type</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                            <label class="form-check-label" for="flexRadioDefault1">
                            BLE
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
                            <label class="form-check-label" for="flexRadioDefault2">
                            NFC
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Led intensity</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                            <label class="form-check-label" for="flexRadioDefault1">
                            Low
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
                            <label class="form-check-label" for="flexRadioDefault2">
                            Hight
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="my-2 col-4">
            <div class="card h-100">
                <div class="card-header bg-light-success text-success text-uppercase d-flex flex-row">
                    <i class="bi bi-battery-charging"></i>
                    <span class="my-auto ms-2" >Battery</span>
                </div>
                <div class="card-body">
                    <div class="form mb-3">
                        <button class="btn btn-primary mb-3">Charge smartcard</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="my-2 col-4">
            <div class="card h-100">
                <div class="card-header bg-light-success text-success text-uppercase d-flex flex-row">
                    <i class="bi bi-ban"></i>
                    <span class="my-auto ms-2" >Security</span>
                </div>
                <div class="card-body">
                    <div class="card-body">
                        <div class="form mb-3">
                            <button class="btn btn-danger mb-3">Block smartcard</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

</script>
