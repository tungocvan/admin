<div class="container-fluid">
    <!--begin::Row-->
    <div class="row g-4">
      <!--begin::Col-->
      <div class="col-12">
        <div class="callout callout-info">
          For detailed documentation of Form visit
          <a href="https://getbootstrap.com/docs/5.3/forms/overview/" target="_blank" rel="noopener noreferrer" class="callout-link">
            Bootstrap Form
          </a>
        </div>
      </div>
      <!--end::Col-->
      <!--begin::Col-->
      <div class="col-md-6">
        <!--begin::Quick Example-->
        <div class="card card-primary card-outline mb-4">
          <!--begin::Header-->
          <div class="card-header">
            <div class="card-title">Quick Example</div>
          </div>
          <!--end::Header-->
          <!--begin::Form-->
          <form>
            <!--begin::Body-->
            <div class="card-body">
              <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email address</label>
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" fdprocessedid="nunttp">
                <div id="emailHelp" class="form-text">
                  We'll never share your email with anyone else.
                </div>
              </div>
              <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1" fdprocessedid="wrhu0e">
              </div>
              <div class="input-group mb-3">
                <input type="file" class="form-control" id="inputGroupFile02">
                <label class="input-group-text" for="inputGroupFile02">Upload</label>
              </div>
              <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1">Check me out</label>
              </div>
            </div>
            <!--end::Body-->
            <!--begin::Footer-->
            <div class="card-footer">
              <button type="submit" class="btn btn-primary" fdprocessedid="77ij9r">Submit</button>
            </div>
            <!--end::Footer-->
          </form>
          <!--end::Form-->
        </div>
        <!--end::Quick Example-->
        <!--begin::Input Group-->
        <div class="card card-success card-outline mb-4">
          <!--begin::Header-->
          <div class="card-header">
            <div class="card-title">Input Group</div>
          </div>
          <!--end::Header-->
          <!--begin::Body-->
          <div class="card-body">
            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1">@</span>
              <input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" fdprocessedid="8bby2d">
            </div>

            <div class="input-group mb-3">
              <input type="text" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="basic-addon2" fdprocessedid="e9foat">
              <span class="input-group-text" id="basic-addon2">@example.com</span>
            </div>

            <div class="mb-3">
              <label for="basic-url" class="form-label">Your vanity URL</label>
              <div class="input-group">
                <span class="input-group-text" id="basic-addon3">https://example.com/users/</span>
                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3 basic-addon4" fdprocessedid="blrtfi">
              </div>
              <div class="form-text" id="basic-addon4">
                Example help text goes outside the input group.
              </div>
            </div>

            <div class="input-group mb-3">
              <span class="input-group-text">$</span>
              <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" fdprocessedid="pu17d6">
              <span class="input-group-text">.00</span>
            </div>

            <div class="input-group mb-3">
              <input type="text" class="form-control" placeholder="Username" aria-label="Username" fdprocessedid="5wkgyk">
              <span class="input-group-text">@</span>
              <input type="text" class="form-control" placeholder="Server" aria-label="Server" fdprocessedid="ljot8e">
            </div>

            <div class="input-group">
              <span class="input-group-text">With textarea</span>
              <textarea class="form-control" aria-label="With textarea"></textarea>
            </div>
          </div>
          <!--end::Body-->
          <!--begin::Footer-->
          <div class="card-footer">
            <button type="submit" class="btn btn-success" fdprocessedid="ahseft">Submit</button>
          </div>
          <!--end::Footer-->
        </div>
        <!--end::Input Group-->
        <!--begin::Horizontal Form-->
        <div class="card card-warning card-outline mb-4">
          <!--begin::Header-->
          <div class="card-header">
            <div class="card-title">Horizontal Form</div>
          </div>
          <!--end::Header-->
          <!--begin::Form-->
          <form>
            <!--begin::Body-->
            <div class="card-body">
              <div class="row mb-3">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                  <input type="email" class="form-control" id="inputEmail3" fdprocessedid="m5flm">
                </div>
              </div>
              <div class="row mb-3">
                <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
                <div class="col-sm-10">
                  <input type="password" class="form-control" id="inputPassword3" fdprocessedid="uivqi9">
                </div>
              </div>
              <fieldset class="row mb-3">
                <legend class="col-form-label col-sm-2 pt-0">Radios</legend>
                <div class="col-sm-10">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios1" value="option1" checked="">
                    <label class="form-check-label" for="gridRadios1"> First radio </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios2" value="option2">
                    <label class="form-check-label" for="gridRadios2"> Second radio </label>
                  </div>
                  <div class="form-check disabled">
                    <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios3" value="option3" disabled="">
                    <label class="form-check-label" for="gridRadios3">
                      Third disabled radio
                    </label>
                  </div>
                </div>
              </fieldset>
              <div class="row mb-3">
                <div class="col-sm-10 offset-sm-2">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="gridCheck1">
                    <label class="form-check-label" for="gridCheck1">
                      Example checkbox
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <!--end::Body-->
            <!--begin::Footer-->
            <div class="card-footer">
              <button type="submit" class="btn btn-warning" fdprocessedid="oumtpj">Sign in</button>
              <button type="submit" class="btn float-end" fdprocessedid="s0tnfn">Cancel</button>
            </div>
            <!--end::Footer-->
          </form>
          <!--end::Form-->
        </div>
        <!--end::Horizontal Form-->
      </div>
      <!--end::Col-->
      <!--begin::Col-->
      <div class="col-md-6">
        <!--begin::Different Height-->
        <div class="card card-secondary card-outline mb-4">
          <!--begin::Header-->
          <div class="card-header">
            <div class="card-title">Different Height</div>
          </div>
          <!--end::Header-->
          <!--begin::Body-->
          <div class="card-body">
            <input class="form-control form-control-lg" type="text" placeholder=".form-control-lg" aria-label=".form-control-lg example" fdprocessedid="3zhvcd">
            <br>
            <input class="form-control" type="text" placeholder="Default input" aria-label="default input example" fdprocessedid="vhvtao">
            <br>
            <input class="form-control form-control-sm" type="text" placeholder=".form-control-sm" aria-label=".form-control-sm example" fdprocessedid="yslvpo">
          </div>
          <!--end::Body-->
        </div>
        <!--end::Different Height-->
        <!--begin::Different Width-->
        <div class="card card-danger card-outline mb-4">
          <!--begin::Header-->
          <div class="card-header">
            <div class="card-title">Different Width</div>
          </div>
          <!--end::Header-->
          <!--begin::Body-->
          <div class="card-body">
            <!--begin::Row-->
            <div class="row">
              <!--begin::Col-->
              <div class="col-3">
                <input type="text" class="form-control" placeholder=".col-3" aria-label=".col-3" fdprocessedid="fsekh">
              </div>
              <!--end::Col-->
              <!--begin::Col-->
              <div class="col-4">
                <input type="text" class="form-control" placeholder=".col-4" aria-label=".col-4" fdprocessedid="yepyug">
              </div>
              <!--end::Col-->
              <!--begin::Col-->
              <div class="col-5">
                <input type="text" class="form-control" placeholder=".col-5" aria-label=".col-5" fdprocessedid="yojgaep">
              </div>
              <!--end::Col-->
            </div>
            <!--end::Row-->
          </div>
          <!--end::Body-->
        </div>
        <!--end::Different Width-->
        <!--begin::Form Validation-->
        <div class="card card-info card-outline mb-4">
          <!--begin::Header-->
          <div class="card-header">
            <div class="card-title">Form Validation</div>
          </div>
          <!--end::Header-->
          <!--begin::Form-->
          <form class="needs-validation" novalidate="">
            <!--begin::Body-->
            <div class="card-body">
              <!--begin::Row-->
              <div class="row g-3">
                <!--begin::Col-->
                <div class="col-md-6">
                  <label for="validationCustom01" class="form-label active">First name<span class="required-indicator sr-only"> (required)</span></label>
                  <input type="text" class="form-control" id="validationCustom01" value="Mark" required="" fdprocessedid="k4i2wi">
                  <div class="valid-feedback">Looks good!</div>
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-md-6">
                  <label for="validationCustom02" class="form-label active">Last name<span class="required-indicator sr-only"> (required)</span></label>
                  <input type="text" class="form-control" id="validationCustom02" value="Otto" required="" fdprocessedid="2gh7uo">
                  <div class="valid-feedback">Looks good!</div>
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-md-6">
                  <label for="validationCustomUsername" class="form-label">Username<span class="required-indicator sr-only"> (required)</span></label>
                  <div class="input-group has-validation">
                    <span class="input-group-text" id="inputGroupPrepend">@</span>
                    <input type="text" class="form-control" id="validationCustomUsername" aria-describedby="inputGroupPrepend" required="" fdprocessedid="h9663f">
                    <div class="invalid-feedback">Please choose a username.</div>
                  </div>
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-md-6">
                  <label for="validationCustom03" class="form-label">City<span class="required-indicator sr-only"> (required)</span></label>
                  <input type="text" class="form-control" id="validationCustom03" required="" fdprocessedid="vq8n4m">
                  <div class="invalid-feedback">Please provide a valid city.</div>
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-md-6">
                  <label for="validationCustom04" class="form-label">State<span class="required-indicator sr-only"> (required)</span></label>
                  <select class="form-select" id="validationCustom04" required="" fdprocessedid="owpe77">
                    <option selected="" disabled="" value="">Choose...</option>
                    <option>...</option>
                  </select>
                  <div class="invalid-feedback">Please select a valid state.</div>
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-md-6">
                  <label for="validationCustom05" class="form-label">Zip<span class="required-indicator sr-only"> (required)</span></label>
                  <input type="text" class="form-control" id="validationCustom05" required="" fdprocessedid="fosycm">
                  <div class="invalid-feedback">Please provide a valid zip.</div>
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-12">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required="">
                    <label class="form-check-label" for="invalidCheck">
                      Agree to terms and conditions
                    <span class="required-indicator sr-only"> (required)</span></label>
                    <div class="invalid-feedback">You must agree before submitting.</div>
                  </div>
                </div>
                <!--end::Col-->
              </div>
              <!--end::Row-->
            </div>
            <!--end::Body-->
            <!--begin::Footer-->
            <div class="card-footer">
              <button class="btn btn-info" type="submit" fdprocessedid="eyf64">Submit form</button>
            </div>
            <!--end::Footer-->
          </form>
          <!--end::Form-->
          <!--begin::JavaScript-->
          <script>
            // Example starter JavaScript for disabling form submissions if there are invalid fields
            (() => {
              'use strict';

              // Fetch all the forms we want to apply custom Bootstrap validation styles to
              const forms = document.querySelectorAll('.needs-validation');

              // Loop over them and prevent submission
              Array.from(forms).forEach((form) => {
                form.addEventListener(
                  'submit',
                  (event) => {
                    if (!form.checkValidity()) {
                      event.preventDefault();
                      event.stopPropagation();
                    }

                    form.classList.add('was-validated');
                  },
                  false,
                );
              });
            })();
          </script>
          <!--end::JavaScript-->
        </div>
        <!--end::Form Validation-->
      </div>
      <!--end::Col-->
    </div>
    <!--end::Row-->
  </div>
