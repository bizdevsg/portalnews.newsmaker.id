# TODO: Remove Decimal Parts from Pivot & Fibonacci Section

## Completed Tasks
- [x] Create migration to change open, high, low, close columns from decimal to string(20)
- [x] Update PivotController validations from 'numeric' to 'string|max:20' for store and update methods
- [x] Change input types in create.blade.php from "number" to "text", remove step="0.01"
- [x] Change input types in edit.blade.php from "number" to "text", remove step="0.01"
- [x] Remove number_format( , 2, '.', '') in index.blade.php, display as raw text
- [x] Run php artisan migrate to apply DB changes

## Followup Steps
- [x] Fix delete button: Change from link to form with POST and _method DELETE, add confirmation
- [ ] Test the forms: Ensure text inputs accept non-numeric values, save without errors, and display as text
- [ ] Verify no data loss (run on dev DB)
- [ ] If fibonacci calculations exist elsewhere (e.g., JS), adjust if needed (none found)
