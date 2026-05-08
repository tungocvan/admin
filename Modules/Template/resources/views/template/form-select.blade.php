<!-- Card Form Interaction -->
<div class="card">
    <div class="card-header">
      <h5 class="card-title">Form interaction</h5>
      <small>Change the values and press reset to restore to initial state.</small>
    </div>
    <div class="card-body">
      <form id="interactionForm">
        <!-- Single Select -->
        <div class="mb-3 row">
          <label for="selectSingle" class="col-form-label col-lg-4 col-sm-12 text-lg-end">Change me!</label>
          <div class="col-lg-6 col-md-11 col-sm-12">
            <select id="selectSingle" name="reset-simple">
              <option value="Option 1">Option 1</option>
              <option value="Option 2">Option 2</option>
              <option value="Option 3">Option 3</option>
              <option value="Option 4" selected>Option 4</option>
              <option value="Option 5">Option 5</option>
            </select>
          </div>
        </div>

        <!-- Multiple Select -->
        <div class="mb-3 row">
          <label for="selectMultiple" class="col-form-label col-lg-4 col-sm-12 text-lg-end">And me!</label>
          <div class="col-lg-6 col-md-11 col-sm-12">
            <select id="selectMultiple" name="reset-multiple" multiple>
              <option value="Choice 1" selected>Choice 1</option>
              <option value="Choice 2">Choice 2</option>
              <option value="Choice 3">Choice 3</option>
              <option value="Choice 4" disabled>Choice 4</option>
            </select>
          </div>
        </div>

        <!-- Reset Button -->
        <div class="row mb-0">
          <div class="col-lg-6 offset-lg-4 col-md-11 col-sm-12">
            <button type="reset" class="btn btn-warning">
              <i class="ph ph-warning me-2"></i> Reset
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Choices.js Initialization -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Single select
      const singleSelect = document.getElementById('selectSingle');
      const singleChoice = new Choices(singleSelect, {
        searchEnabled: true,
        itemSelectText: '',
        shouldSort: false
      });

      // Multiple select
      const multipleSelect = document.getElementById('selectMultiple');
      const multipleChoice = new Choices(multipleSelect, {
        removeItemButton: true,
        searchEnabled: true,
        shouldSort: false
      });

      // Reset form
      const form = document.getElementById('interactionForm');
      form.addEventListener('reset', () => {
        setTimeout(() => {
          singleChoice.setChoiceByValue('Option 4');
          multipleChoice.removeActiveItems();
          multipleChoice.setChoiceByValue('Choice 1');
        }, 0);
      });
    });
  </script>

  <!-- Make sure to include Choices.js CSS & JS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
