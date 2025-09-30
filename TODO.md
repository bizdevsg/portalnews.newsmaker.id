# TODO: Fix Pivot & Fibonacci Decimal to Text Conversion

## Tasks
- [x] Update PivotController.php validation rules for open, high, low, close from numeric to string
- [x] Update index.blade.php to remove number_format and display raw text values
- [x] Update create.blade.php to change number inputs to text inputs and remove step attribute
- [x] Update edit.blade.php to change number inputs to text inputs and remove step attribute
- [x] Add Chg column before Volume for HSI Daily category
- [x] Update validation to include chg for HSI Daily
- [x] Run migration to add chg column
- [x] Clear Laravel caches

## Follow-up
- [x] Test form input and display to confirm decimals replaced by text
- [x] Confirm no validation errors occur for text inputs
- [x] Test that index page defaults to the category of created/edited/deleted item
- [x] Add volume and open_interest columns for HSI Daily category
