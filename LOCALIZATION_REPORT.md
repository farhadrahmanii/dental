# FilamentPHP Localization Report

## Summary
This report identifies all remaining hardcoded English strings in FilamentPHP resources, pages, relation managers, widgets, and tables that need to be localized.

## Files with Hardcoded Strings

### 1. **PatientResource.php** (app/Filament/Resources/PatientResource.php)
- Line 340: `->modalHeading('Add Transcription')` → Should use `__('filament.add_transcription')`
- Line 361: `->title('Error')` → Should use `__('filament.error')`
- Line 362: `->body('Patient record not found.')` → Needs new translation key
- Line 384: `->title('Transcription added')` → Needs new translation key
- Line 385: `->body('The transcription has been added successfully.')` → Needs new translation key

### 2. **CreateTranscription.php** (app/Filament/Resources/PatientResource/Pages/CreateTranscription.php)
- Line 58: `->title('Transcription added')` → Needs new translation key
- Line 59: `->body('The transcription has been added successfully.')` → Needs new translation key
- Line 67: `return 'Add Transcription for ' . ($this->record->name ?? 'Patient');` → Needs localization
- Line 75: `->label('Transcription Text')` → Should use `__('filament.transcription_text')`
- Line 76: `->placeholder('Enter the transcription text...')` → Needs new translation key
- Line 81: `->label('Recorded By')` → Should use `__('filament.recorded_by')`
- Line 82: `->placeholder('e.g. Dr. Ahmad Zai')` → Needs new translation key
- Line 86: `->label('Date')` → Should use `__('filament.date')`

### 3. **CreateXray.php** (app/Filament/Resources/PatientResource/Pages/CreateXray.php)
- Line 54: `->title('X-ray added')` → Needs new translation key
- Line 55: `->body('The X-ray has been added successfully.')` → Needs new translation key
- Line 63: `return 'Add X-ray for ' . ($this->record->name ?? 'Patient');` → Needs localization
- Line 71: `->label('X-ray Image')` → Needs new translation key
- Line 81: `->label('Treatment')` → Should use `__('filament.treatment')`
- Line 82: `->placeholder('e.g. Root canal')` → Needs new translation key
- Line 87: `->label('Doctor Name')` → Should use `__('filament.doctor_name')`
- Line 88: `->placeholder('e.g. Dr. Ahmad Zai')` → Needs new translation key
- Line 93: `->label('Comment (optional)')` → Should use `__('filament.comment_optional')`
- Line 94: `->placeholder('Notes about this X-ray (optional)')` → Needs new translation key

### 4. **ViewPatient.php** (app/Filament/Resources/PatientResource/Pages/ViewPatient.php)
- Line 28: `->label('Collect Payment')` → Should use `__('filament.collect_payment')`
- Line 33: `->label('Payment Amount')` → Should use `__('filament.payment_amount')`
- Line 38: `->placeholder('0.00')` → Can remain as is (numeric)
- Line 42: `->label('Payment Method')` → Should use `__('filament.payment_method')`
- Lines 44-48: Payment method options → Should use existing translation keys
- Line 55: `->label('Payment Date')` → Should use `__('filament.payment_date')`
- Line 61: `->label('Reference Number')` → Should use `__('filament.reference_number')`
- Line 63: `->placeholder('Optional reference number')` → Needs new translation key
- Line 66: `->label('Notes')` → Should use `__('filament.notes')`
- Line 67: `->placeholder('Add any notes about this payment...')` → Needs new translation key
- Line 81: `->title('Payment Recorded Successfully')` → Needs new translation key
- Line 83: `->body('Payment of ...')` → Needs new translation key (dynamic message)
- Line 86: `->modalHeading('Collect Payment from Patient')` → Needs new translation key
- Line 87: `->modalDescription('Record a new payment for this patient')` → Needs new translation key
- Line 88: `->modalSubmitActionLabel('Record Payment')` → Needs new translation key
- Line 92: `->label('View Financials')` → Needs new translation key

### 5. **TreatmentsRelationManager.php** (app/Filament/Resources/PatientResource/RelationManagers/TreatmentsRelationManager.php)
- Line 20: `protected static ?string $title = 'Treatments';` → Should use `__('filament.treatments')`
- Line 22: `protected static ?string $modelLabel = 'Treatment';` → Should use `__('filament.treatment')`
- Line 24: `protected static ?string $pluralModelLabel = 'Treatments';` → Should use `__('filament.treatments')`
- Line 36: `->label('Service')` → Should use `__('filament.service')`
- Line 41: `->label('Select Teeth on Chart (Visual)')` → Needs new translation key
- Line 50: `->helperText('Click on the teeth to visually mark which teeth are being treated')` → Needs new translation key
- Line 52: `->label('Tooth Numbers (Manual Selection)')` → Needs new translation key
- Line 59: `->label('Treatment Date')` → Should use `__('filament.treatment_date')`
- Line 62: `->label('Treatment Description')` → Should use `__('filament.treatment_description')`
- Line 73: `->label('Service')` → Should use `__('filament.service')`
- Line 77: `->label('Tooth Numbers')` → Should use `__('filament.tooth_numbers')`
- Line 87: `->label('Treatment Date')` → Should use `__('filament.treatment_date')`
- Line 91: `->label('Description')` → Should use `__('filament.description')`
- Line 95: `->label('Created')` → Should use `__('filament.created')`

### 6. **PaymentsRelationManager.php** (app/Filament/Resources/PatientResource/RelationManagers/PaymentsRelationManager.php)
- Line 20: `protected static ?string $title = 'Payments';` → Should use `__('filament.payments')`
- Line 22: `protected static ?string $modelLabel = 'Payment';` → Should use `__('filament.payment')`
- Line 24: `protected static ?string $pluralModelLabel = 'Payments';` → Should use `__('filament.payments')`
- Line 31: `->label('Type')` → Should use `__('filament.type')`
- Lines 33-35: Type options → Should use existing translation keys
- Lines 45-49: Payment method options → Should use existing translation keys
- Line 67: `->label('Type')` → Should use `__('filament.type')`
- Lines 76-79: Type formatStateUsing → Should use existing translation keys
- Line 100: `->label('Invoice')` → Should use `__('filament.invoice')`
- Lines 110-114: Payment method filter options → Should use existing translation keys

### 7. **XraysRelationManager.php** (app/Filament/Resources/PatientResource/RelationManagers/XraysRelationManager.php)
- Line 21: `->label('X-ray Image')` → Needs new translation key
- Line 49: `->label('X-ray')` → Should use `__('filament.xray')`

### 8. **TranscriptionsRelationManager.php** (app/Filament/Resources/PatientResource/RelationManagers/TranscriptionsRelationManager.php)
- Line 23: `->label('Transcription Text')` → Should use `__('filament.transcription_text')`
- Line 28: `->label('Recorded By')` → Should use `__('filament.recorded_by')`
- Line 33: `->label('Date')` → Should use `__('filament.date')`
- Line 46: `->label('Transcription ID')` → Needs new translation key
- Line 50: `->label('Transcription')` → Needs new translation key
- Line 55: `->label('Recorded By')` → Should use `__('filament.recorded_by')`
- Line 58: `->label('Date')` → Should use `__('filament.date')`
- Line 62: `->label('Created')` → Should use `__('filament.created')`

### 9. **InvoicesRelationManager.php** (app/Filament/Resources/PatientResource/RelationManagers/InvoicesRelationManager.php)
- Line 20: `protected static ?string $title = 'Invoices';` → Should use `__('filament.invoices')`
- Line 22: `protected static ?string $modelLabel = 'Invoice';` → Should use `__('filament.invoice')`

### 10. **InvoicesTable.php** (app/Filament/Resources/Invoices/Tables/InvoicesTable.php)
- Line 26: `->label('Invoice #')` → Needs new translation key
- Line 31: `->tooltip('Click to copy invoice number')` → Needs new translation key
- Line 34: `->label('Patient')` → Should use `__('filament.patient')`
- Line 39: `->tooltip('Click to copy patient name')` → Needs new translation key
- Line 42: `->label('Invoice Date')` → Needs new translation key
- Line 48: `->label('Due Date')` → Needs new translation key
- Line 54: `->label('Total Amount')` → Needs new translation key
- Line 62: `->label('Balance')` → Needs new translation key
- Line 70: `->label('Status')` → Should use `__('filament.status')`
- Lines 78-82: Status formatStateUsing → Should use existing translation keys
- Line 87: `->label('Created')` → Should use `__('filament.created')`
- Line 93: `->label('Updated')` → Should use `__('filament.updated')`
- Line 100: `->label('Status')` → Should use `__('filament.status')`
- Lines 102-106: Status filter options → Should use existing translation keys
- Line 110: `->label('Invoice Date')` → Needs new translation key
- Line 113: `->label('Invoice Date')` → Needs new translation key
- Line 124: `->label('Due Date')` → Needs new translation key
- Line 127: `->label('Due Date')` → Needs new translation key
- Line 138: `->label('Amount Range')` → Should use `__('filament.amount_range')`
- Line 157: `->label('Overdue')` → Should use `__('filament.overdue')`
- Line 158: `->placeholder('All invoices')` → Needs new translation key
- Line 159: `->trueLabel('Overdue only')` → Needs new translation key
- Line 160: `->falseLabel('Not overdue')` → Needs new translation key
- Line 173: `->label('View Invoice')` → Needs new translation key
- Line 175: `->label('Edit Invoice')` → Needs new translation key
- Line 177: `->label('Delete Invoice')` → Needs new translation key
- Line 183: `->label('Delete Selected')` → Should use `__('filament.delete_selected')`

### 11. **InvoiceResource.php** (app/Filament/Resources/Invoices/InvoiceResource.php)
- Line 23: `protected static ?string $navigationLabel = 'Invoices';` → Should use `__('filament.invoices')`
- Line 25: `protected static ?string $modelLabel = 'Invoice';` → Should use `__('filament.invoice')`
- Line 27: `protected static ?string $pluralModelLabel = 'Invoices';` → Should use `__('filament.invoices')`

### 12. **InvoiceForm.php** (app/Filament/Resources/Invoices/Schemas/InvoiceForm.php)
- Line 20: `->label('Patient')` → Should use `__('filament.patient')`
- Line 25: `->placeholder('Select a patient')` → Should use `__('filament.select_patient')`
- Line 28: `->label('Invoice Date')` → Needs new translation key
- Line 34: `->label('Due Date')` → Needs new translation key
- Line 40: `->label('Status')` → Should use `__('filament.status')`
- Line 52: `->label('Notes')` → Should use `__('filament.notes')`
- Line 54: `->placeholder('Additional notes about this invoice')` → Needs new translation key

### 13. **ExpenseSummaryWidget.php** (app/Filament/Widgets/ExpenseSummaryWidget.php)
- Line 53: `Stat::make('Monthly Expenses', ...)` → Needs new translation key
- Line 54: Description with percentage → Needs new translation key
- Line 59: `Stat::make('Total Expenses', ...)` → Needs new translation key
- Line 60: `->description('All time expenses')` → Needs new translation key
- Line 65: `Stat::make('Today\'s Expenses', ...)` → Needs new translation key
- Line 66: `->description('Spent today')` → Needs new translation key
- Line 71: `Stat::make('Avg Expense (This Month)', ...)` → Needs new translation key
- Line 72: `->description('Per transaction average')` → Needs new translation key

### 14. **RevenueStatsWidget.php** (app/Filament/Widgets/RevenueStatsWidget.php)
- Line 62: `Stat::make('Total Revenue', ...)` → Needs new translation key
- Line 63: `->description('All time earnings')` → Needs new translation key
- Line 68: `Stat::make('Monthly Revenue', ...)` → Needs new translation key
- Line 74: `Stat::make('Today\'s Revenue', ...)` → Needs new translation key
- Line 75: `->description('Collected today')` → Needs new translation key
- Line 80: `Stat::make('Expected Revenue', ...)` → Needs new translation key
- Line 81: `->description('From upcoming appointments')` → Needs new translation key

### 15. **ServiceStatsWidget.php** (app/Filament/Widgets/ServiceStatsWidget.php)
- Line 40: `Stat::make('Total Services', ...)` → Needs new translation key
- Line 41: `->description("{$activeServices} active services")` → Needs new translation key
- Line 46: `Stat::make('Average Price', ...)` → Needs new translation key
- Line 47: `->description('Per service')` → Needs new translation key
- Line 60: `Stat::make('Most Booked', ...)` → Needs new translation key
- Line 61: Description with bookings count → Needs new translation key
- Line 66: `Stat::make('Service Revenue', ...)` → Needs new translation key
- Line 67: `->description('This month from services')` → Needs new translation key

### 16. **AppointmentStatsWidget.php** (app/Filament/Widgets/AppointmentStatsWidget.php)
- Line 57: `Stat::make('Today\'s Appointments', ...)` → Needs new translation key
- Line 58: Description with completed count → Needs new translation key
- Line 63: `Stat::make('Upcoming Appointments', ...)` → Needs new translation key
- Line 64: `->description('Scheduled for future')` → Needs new translation key
- Line 69: `Stat::make('Completed This Month', ...)` → Needs new translation key
- Line 75: `Stat::make('Pending Confirmations', ...)` → Needs new translation key
- Line 76: `->description('Awaiting confirmation')` → Needs new translation key

### 17. **PatientStatsWidget.php** (app/Filament/Widgets/PatientStatsWidget.php)
- Line 53: `->description('All registered patients')` → Needs new translation key
- Line 65: `->description('Had appointments in last 3 months')` → Needs new translation key
- Line 71: `->description('Scheduled for today')` → Needs new translation key

### 18. **UpcomingAppointmentsWidget.php** (app/Filament/Widgets/UpcomingAppointmentsWidget.php)
- Line 32: `->label('Date')` → Should use `__('filament.date')`
- Line 38: `->label('Time')` → Should use `__('filament.time')`
- Line 43: `->label('Patient')` → Should use `__('filament.patient')`
- Line 48: `->label('Service')` → Should use `__('filament.service')`
- Line 54: `->label('Status')` → Should use `__('filament.status')`
- Line 63: `->label('View')` → Should use `__('filament.view')`

### 19. **TodayAppointmentsWidget.php** (app/Filament/Widgets/TodayAppointmentsWidget.php)
- Line 30: `->label('Time')` → Should use `__('filament.time')`
- Line 37: `->label('Patient')` → Should use `__('filament.patient')`
- Line 43: `->label('Service')` → Should use `__('filament.service')`
- Line 49: `->label('Status')` → Should use `__('filament.status')`
- Line 60: `->label('Patient ID')` → Needs new translation key
- Line 65: `->label('Notes')` → Should use `__('filament.notes')`
- Line 78: `->label('View')` → Should use `__('filament.view')`
- Line 83: `->label('Confirm')` → Needs new translation key
- Line 91: `->label('Complete')` → Needs new translation key

### 20. **ExpenseCategoryWidget.php** (app/Filament/Widgets/ExpenseCategoryWidget.php)
- Line 33: `->label('Category')` → Should use `__('filament.category')`
- Line 40: `->label('Transactions')` → Needs new translation key
- Line 45: `->label('Total Amount')` → Needs new translation key

### 21. **RecentPaymentsWidget.php** (app/Filament/Widgets/RecentPaymentsWidget.php)
- Line 29: `->label('Date')` → Should use `__('filament.date')`
- Line 35: `->label('Patient')` → Should use `__('filament.patient')`
- Line 40: `->label('Service')` → Should use `__('filament.service')`
- Line 47: `->label('Amount')` → Should use `__('filament.amount')`
- Line 54: `->label('Method')` → Should use `__('filament.method')`
- Line 64: `->label('Appointment #')` → Should use `__('filament.appt')`

## Required Translation Keys

The following new translation keys need to be added to `resources/lang/en/filament.php` (and corresponding files for `ps` and `fa`):

### Notifications & Messages
- `transcription_added` => 'Transcription added'
- `transcription_added_successfully` => 'The transcription has been added successfully.'
- `xray_added` => 'X-ray added'
- `xray_added_successfully` => 'The X-ray has been added successfully.'
- `payment_recorded_successfully` => 'Payment Recorded Successfully'
- `payment_recorded_message` => 'Payment of :amount has been recorded for :patient'
- `patient_record_not_found` => 'Patient record not found.'
- `add_transcription_for` => 'Add Transcription for :patient'
- `add_xray_for` => 'Add X-ray for :patient'

### Form Labels & Placeholders
- `xray_image` => 'X-ray Image'
- `comment_optional` => 'Comment (optional)'
- `transcription_id` => 'Transcription ID'
- `transcription` => 'Transcription'
- `invoice_number` => 'Invoice #'
- `invoice_date` => 'Invoice Date'
- `due_date` => 'Due Date'
- `total_amount` => 'Total Amount'
- `balance` => 'Balance'
- `patient_id` => 'Patient ID'
- `click_to_copy_invoice` => 'Click to copy invoice number'
- `click_to_copy_patient` => 'Click to copy patient name'
- `optional_reference_number` => 'Optional reference number'
- `add_payment_notes_placeholder` => 'Add any notes about this payment...'
- `collect_payment_from_patient` => 'Collect Payment from Patient'
- `record_new_payment` => 'Record a new payment for this patient'
- `record_payment` => 'Record Payment'
- `view_financials` => 'View Financials'
- `enter_transcription_text` => 'Enter the transcription text...'
- `doctor_name_example` => 'e.g. Dr. Ahmad Zai'
- `treatment_example` => 'e.g. Root canal'
- `xray_notes_placeholder` => 'Notes about this X-ray (optional)'
- `select_teeth_visual` => 'Select Teeth on Chart (Visual)'
- `click_teeth_helper` => 'Click on the teeth to visually mark which teeth are being treated'
- `tooth_numbers_manual` => 'Tooth Numbers (Manual Selection)'
- `additional_invoice_notes` => 'Additional notes about this invoice'

### Widget Labels
- `monthly_expenses` => 'Monthly Expenses'
- `total_expenses` => 'Total Expenses'
- `todays_expenses` => 'Today\'s Expenses'
- `avg_expense_month` => 'Avg Expense (This Month)'
- `all_time_expenses` => 'All time expenses'
- `spent_today` => 'Spent today'
- `per_transaction_average` => 'Per transaction average'
- `total_revenue` => 'Total Revenue'
- `monthly_revenue` => 'Monthly Revenue'
- `todays_revenue` => 'Today\'s Revenue'
- `expected_revenue` => 'Expected Revenue'
- `all_time_earnings` => 'All time earnings'
- `collected_today` => 'Collected today'
- `from_upcoming_appointments` => 'From upcoming appointments'
- `total_services` => 'Total Services'
- `average_price` => 'Average Price'
- `most_booked` => 'Most Booked'
- `service_revenue` => 'Service Revenue'
- `active_services` => ':count active services'
- `per_service` => 'Per service'
- `bookings_this_month` => ':count bookings this month'
- `this_month_from_services` => 'This month from services'
- `todays_appointments` => 'Today\'s Appointments'
- `upcoming_appointments` => 'Upcoming Appointments'
- `completed_this_month` => 'Completed This Month'
- `pending_confirmations` => 'Pending Confirmations'
- `completed_today` => ':count completed today'
- `no_completed_yet` => 'No completed yet'
- `scheduled_for_future` => 'Scheduled for future'
- `awaiting_confirmation` => 'Awaiting confirmation'
- `all_registered_patients` => 'All registered patients'
- `had_appointments_last_3_months` => 'Had appointments in last 3 months'
- `scheduled_for_today` => 'Scheduled for today'
- `transactions` => 'Transactions'
- `confirm` => 'Confirm'
- `complete` => 'Complete'

### Filter & Action Labels
- `all_invoices` => 'All invoices'
- `overdue_only` => 'Overdue only'
- `not_overdue` => 'Not overdue'
- `view_invoice` => 'View Invoice'
- `edit_invoice` => 'Edit Invoice'
- `delete_invoice` => 'Delete Invoice'

### Status Formatting
- Status values in formatStateUsing should use existing keys: `paid`, `pending`, `overdue`, `cancelled`

## Priority Files to Localize

1. **High Priority** (User-facing notifications and labels):
   - PatientResource.php (notifications)
   - CreateTranscription.php
   - CreateXray.php
   - ViewPatient.php
   - TreatmentsRelationManager.php
   - InvoicesTable.php

2. **Medium Priority** (Relation managers and widgets):
   - PaymentsRelationManager.php
   - XraysRelationManager.php
   - TranscriptionsRelationManager.php
   - All Widget files
   - InvoiceForm.php

3. **Low Priority** (Static labels):
   - InvoiceResource.php
   - InvoicesRelationManager.php

## Notes

- Some placeholders like `'0.00'` can remain as-is since they're numeric
- Dynamic messages with variables (like payment amounts) need special handling with placeholders
- Some strings like `'System'` in CreateExpense.php might be acceptable as-is
- Percentage descriptions in widgets need dynamic formatting support

