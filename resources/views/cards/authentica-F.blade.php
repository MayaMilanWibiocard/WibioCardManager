
<div class="mx-auto sm:px-6 lg:px-8" id="SMtypeC">
    <div class="row">
        <div class="col-3">
            <div class="card h-100">
                <div class="card-header bg-light-success text-success text-uppercase d-flex flex-row">
                    <i class="bi bi-credit-card"></i>
                    <span  class="my-auto ms-2" ><b>Type:</b> F</span>
                </div>
                <div class="card-body">
                    <img src="images/type F.png" alt="Wibio smartcard type-F" class="mx-auto my-auto">
                </div>
                <div class="card-footer text-muted">Card state: <span id="cardstate">present</span></div>
            </div>
        </div>
        <div class="col-6">
            <div class="card h-100">
                <div class="card-header bg-light-success text-success text-uppercase d-flex flex-row">
                    <i class="bi bi-fingerprint"></i>
                    <span id="cardid" class="my-auto ms-2" >Otp sequences</span>
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
                            <div class="text-center">
                                <h2 id="otp" class="text-success">OTP: 123456</h2>
                                <section class="countdown-container">
                                    <div class="seconds-container">
                                      <div class="seconds"></div>
                                    </div>
                                </section>
                            </div>
                        </div>
                        <div class="col-4 sensor_container">
                            <img src="images/fingerprint_partial.png" id="imgfinger" class="mx-auto" alt="Wibio fingerprint request">
                        </div>
                    </div>
                </div>
                <div class="card-footer text-muted">Select a sequence and click on button to retrive OTP from smartcard</div>
            </div>
        </div>
        <div class="col-3">
            <div class="card h-100">
                <div class="card-header bg-light-success text-success text-uppercase d-flex flex-row">
                    <i class="bi bi-ban"></i>
                    <span id="cardid" class="my-auto ms-2" >Security</span>
                </div>
                <div class="card-body">
                    <div class="card-body">
                        <div class="form mb-3">
                            <button class="my-2 btn btn-primary w-100">Card management</button>
                            <button class="btn btn-danger w-100">Block smartcard</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
