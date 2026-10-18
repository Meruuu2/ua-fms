# Need to Fix and Update

Status of the known issues in [`documentation.md` §13](documentation.md#13-known-issues-and-backend-follow-ups), checked against the current code and local database on **October 19, 2026**.

**Summary:** 2 fixed, 10 still open.

| # | Issue | Fixed? | Evidence |
|---|---|---|---|
| 1 | Repair (RM) ticket lookup | ✅ Yes | Fixed on Oct 19 and tested: the landing page tracker shows Notified, Pending, Ongoing, Success and Failed. |
| 2 | Dashboard repair counts | ❌ No | The Admin, College and Org dashboards still count `in_progress`, `completed` and `rejected`, but tickets are saved as `ongoing`, `success` and `failed`. The College dashboard also counts only the signed-in user's tickets, even though repair tickets are now shared within the college. |
| 3 | Deleting an account deletes its history | ❌ No (by choice) | Admin delete still removes the account's reservations, requests and repair tickets, as decided. The warning dialog lists them first. |
| 4 | Multiple facilities per request | ❌ No | The College/Org request still holds one facility. |
| 5 | PDF shows only the start date | ❌ No | The PDF generator never reads the end date of a multi-day request. |
| 6 | Guests email their payment receipt | ❌ No | The public form still tells guests to email the receipt. There is no upload. |
| 7 | Landing header on phones | ✅ Yes | Checked at phone width (390px): no overlap, no sideways scroll. **New small gap:** the header links are hidden on phones, and there's no menu button to reach them. |
| 8 | Wrong data ("CCIS Dean") | ❌ No | John C. Amar's college is still "CCIS Dean" (should be "CCIS"), and the CEA Dean is still named "CCIS Dean" in the signatories table. |
| 9 | Wrong dashboard and Monthly Records numbers | ❌ No | Same cause as item 2. Monthly Records also still has a hard-coded `'Paghiusa hall'` venue (`resources/views/admin/monthly-records/index.blade.php:186`). |
| 10 | Approve / Disapprove in one click | ❌ No | No confirmation and no reason yet. |
| 11 | Reserved requests disappear from My requests | ❌ No | Both College and Org still hide `reserved` requests. |
| — | "Control No." shows the word "Pending" | ❌ No | `resources/views/admin/forms/facilities_index.blade.php:134` still shows "Pending" when a request has no control number yet. |

## Where each fix lives

| # | Needs | Files |
|---|---|---|
| 2, 9 | Backend sign-off | `app/Http/Controllers/Admin/DashboardController.php`, `College/DashboardController.php`, `Org/DashboardController.php`, `Admin/MonthlyRecordsController.php`, `resources/views/admin/monthly-records/index.blade.php` |
| 3 | A decision (keep as is, block, or deactivate) | `app/Http/Controllers/Admin/UserController.php` |
| 4 | Backend sign-off (larger change) | College/Org request form, `StoreFacilitiesUtilizationRequest`, `BookingService`, PDF |
| 5 | Backend sign-off | `app/Http/Controllers/Admin/FacilitiesFormPdfController.php` |
| 6 | Backend sign-off | `app/Http/Controllers/PublicFacilityRequestController.php`, `resources/views/public/request-facility.blade.php` |
| 7 (new gap) | UI only | `resources/views/layouts/partials/header.blade.php`, `resources/css/app.css` |
| 8 | Data only | Admin → Users → Edit (John C. Amar), and the `signatories` table (id 3) |
| 10 | UI + small backend (to save the reason) | `resources/views/admin/forms/facilities_show.blade.php`, `Admin/FormSubmissionController.php` |
| 11 | Backend sign-off (or a UI-only wording fix) | `College/FormController.php`, `Org/FormController.php`, `college|org/requests/facilities_index.blade.php` |
| — | UI only | `resources/views/admin/forms/facilities_index.blade.php` |

**Next steps already agreed** (from the Oct 19 critique): items 2 and 9, 10, and 11.
