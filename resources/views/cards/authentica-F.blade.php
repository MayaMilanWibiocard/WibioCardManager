
<div class="mx-auto sm:px-6 lg:px-8" id="SMtypeC">
    <div class="row">
        <div class="col-3">
            <div class="card h-100">
                <div class="card-header bg-light-success text-success text-uppercase d-flex flex-row">
                    <i class="bi bi-credit-card"></i>
                    <span  class="my-auto ms-2" ><b>Type:</b> F</span>
                </div>
                <div class="card-body">
                    <img src="{{ Vite::asset('resources/images/type F.png') }}" alt="Wibio smartcard type-F" class="mx-auto my-auto">
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
                            <select class="form-select" size="10"  id="sequences" aria-label="size 3 select example">
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
                            <img src="{{ Vite::asset('resources/images/fingerprint_partial.png') }}" id="imgfinger" class="mx-auto display-none" alt="Wibio fingerprint request">
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
                            <button class="btn btn-danger w-100">Block smartcard</button>
                            <p class="text-muted text-sm">This action will block the smartcard but to perform this operation you need to verify the fingerprint. <br/> If you have issues with the fingerprint sensor on your card please contact your security administrator.</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-muted">User email: <span id="user_email">{{ Auth::user()->email }}</span></div>
            </div>
        </div>
    </div>
</div>
