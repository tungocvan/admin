<div class="row">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Custom Forms</h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <!-- Custom Select -->
                <div class="col-md-6">
                    <h5>Custom Select</h5>
                    <hr>
                    <!-- Select with label prepended -->
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="select01">Options</label>
                        <select class="form-select" id="select01">
                            <option selected>Choose...</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                    <!-- Select with label appended -->
                    <div class="input-group mb-3">
                        <select class="form-select" id="select02">
                            <option selected>Choose...</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                        <label class="input-group-text" for="select02">Options</label>
                    </div>
                    <!-- Select with button prepended -->
                    <div class="input-group mb-3">
                        <button class="btn btn-outline-secondary" type="button">Button</button>
                        <select class="form-select" id="select03" aria-label="Select with button">
                            <option selected>Choose...</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                    <!-- Select with button appended -->
                    <div class="input-group">
                        <select class="form-select" id="select04" aria-label="Select with button">
                            <option selected>Choose...</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                        <button class="btn btn-outline-secondary" type="button">Button</button>
                    </div>
                </div>

                <!-- Custom File Input -->
                <div class="col-md-6">
                    <h5>Custom File Input</h5>
                    <hr>
                    <!-- File input with label prepended -->
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="file01">Upload</label>
                        <input type="file" class="form-control" id="file01">
                    </div>
                    <!-- File input with label appended -->
                    <div class="input-group mb-3">
                        <input type="file" class="form-control" id="file02">
                        <label class="input-group-text" for="file02">Upload</label>
                    </div>
                    <!-- File input with button prepended -->
                    <div class="input-group mb-3">
                        <button class="btn btn-outline-secondary" type="button">Button</button>
                        <input type="file" class="form-control" id="file03" aria-label="Upload">
                    </div>
                    <!-- File input with button appended -->
                    <div class="input-group">
                        <input type="file" class="form-control" id="file04" aria-label="Upload">
                        <button class="btn btn-outline-secondary" type="button">Button</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-2">
        <div class="card-header">
            <h5 class="card-title">Checks and Radios</h5>
        </div>
        <div class="card-body">
            <div class="row g-4">

                <!-- Checkboxes -->
                <div class="col-md-6">
                    <h5>Checkboxes</h5>
                    <hr>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="customCheck1">
                        <label class="form-check-label" for="customCheck1">Check this custom checkbox</label>
                    </div>
                </div>

                <!-- Switches -->
                <div class="col-md-6">
                    <h5>Switches</h5>
                    <hr>
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input" id="customSwitch1">
                        <label class="form-check-label" for="customSwitch1">Check this custom switch</label>
                    </div>
                </div>

                <!-- Radios -->
                <div class="col-md-6">
                    <h5>Radios</h5>
                    <hr>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="radioDefault" id="radio1">
                        <label class="form-check-label" for="radio1">Default radio</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="radioDefault" id="radio2" checked>
                        <label class="form-check-label" for="radio2">Default checked radio</label>
                    </div>

                    <h5 class="mt-3">Inline Radios</h5>
                    <hr>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="radioInline" id="radioInline1">
                        <label class="form-check-label" for="radioInline1">Default radio</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="radioInline" id="radioInline2" checked>
                        <label class="form-check-label" for="radioInline2">Default checked radio</label>
                    </div>
                </div>

                <!-- Range Inputs -->
                <div class="col-md-6">
                    <h5>Range Inputs</h5>
                    <hr>
                    <div class="mb-3">
                        <label for="range1" class="form-label">Example range</label>
                        <input type="range" class="form-range" id="range1">
                    </div>
                    <div class="mb-3">
                        <label for="range2" class="form-label">Min and max</label>
                        <input type="range" class="form-range" min="0" max="5" id="range2">
                    </div>
                    <div class="mb-3">
                        <label for="range3" class="form-label">Steps</label>
                        <input type="range" class="form-range" min="0" max="5" step="0.5"
                            id="range3">
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="card mt-2">
    <div class="card-header">
        <h5 class="card-title">Button Addons</h5>

    </div>
    <div class="card-body">
        <div class="row g-4">
            <!-- Buttons with Input -->
            <div class="col-md-6">
                <div class="input-group mb-3">
                    <button class="btn btn-outline-secondary" type="button" id="button1">Button</button>
                    <input type="text" class="form-control" placeholder=""
                        aria-label="Example text with button addon" aria-describedby="button1">
                </div>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Recipient's username"
                        aria-label="Recipient's username" aria-describedby="button2">
                    <button class="btn btn-outline-secondary" type="button" id="button2">Button</button>
                </div>
            </div>

            <!-- Multiple Buttons with Input -->
            <div class="col-md-6">
                <div class="input-group mb-3">
                    <button class="btn btn-primary" type="button">Button</button>
                    <button class="btn btn-outline-primary" type="button">Button</button>
                    <input type="text" class="form-control" placeholder=""
                        aria-label="Example text with two button addons">
                </div>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Recipient's username"
                        aria-label="Recipient's username with two button addons">
                    <button class="btn btn-secondary" type="button">Button</button>
                    <button class="btn btn-outline-secondary" type="button">Button</button>
                </div>
            </div>

            <!-- Buttons With Dropdowns -->
            <div class="col-md-6 mt-3">
                <h5>Buttons With Dropdowns</h5>
                <hr>
                <div class="input-group mb-3">
                    <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">Dropdown</button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Separated link</a></li>
                    </ul>
                    <input type="text" class="form-control" aria-label="Text input with dropdown button">
                </div>

                <div class="input-group mb-3">
                    <input type="text" class="form-control" aria-label="Text input with dropdown button">
                    <button class="btn btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">Dropdown</button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Separated link</a></li>
                    </ul>
                </div>

                <div class="input-group">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">Dropdown</button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Action before</a></li>
                        <li><a class="dropdown-item" href="#">Another action before</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Separated link</a></li>
                    </ul>
                    <input type="text" class="form-control" aria-label="Text input with 2 dropdown buttons">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">Dropdown</button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Separated link</a></li>
                    </ul>
                </div>
            </div>

            <!-- Segmented Buttons -->
            <div class="col-md-6 mt-3">
                <h5>Segmented Buttons</h5>
                <hr>
                <div class="input-group mb-3">
                    <button type="button" class="btn btn-outline-secondary">Action</button>
                    <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="visually-hidden">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Separated link</a></li>
                    </ul>
                    <input type="text" class="form-control"
                        aria-label="Text input with segmented dropdown button">
                </div>

                <div class="input-group">
                    <input type="text" class="form-control"
                        aria-label="Text input with segmented dropdown button">
                    <button type="button" class="btn btn-outline-secondary">Action</button>
                    <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="visually-hidden">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Separated link</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
