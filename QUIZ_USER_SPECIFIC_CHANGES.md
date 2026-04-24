# Quiz User-Specific Backend Implementation

## Overview
The diagnostic quiz system has been updated to support user-type-specific questions. Previously, all users saw the same questions. Now:
- **Question 1 (Common)**: Shown to all users (role selection)
- **Questions 2-4 (User-Specific)**: Shown based on user's selected type (doctor, lab, dealer, other)

## Database Changes

### Migration File
**File**: `database/migrations/2026_03_24_000300_create_diagnostic_quiz_tables.php`

**Changes**:
- Added `user_type` column (varchar 50) to `diagnostic_quiz_questions` table
- Added `user_type` column (varchar 50) to `diagnostic_quiz_responses` table
- Updated unique constraint on diagnostic_quiz_questions to include user_type + display_order
- Added index on user_type for both tables for efficient filtering

**Values**: `'common'`, `'doctor'`, `'lab'`, `'dealer'`, `'other'`

## Model Changes

### QuizeQuestion Model
**File**: `app/Models/Quize/QuizeQuestion.php`
```php
protected $fillable = [
    'user_type',
    'phase_title',
    'question_text',
    'question_support_details',
    'display_order',
];
```

### QuizeResponse Model
**File**: `app/Models/Quize/QuizeResponse.php`
```php
protected $fillable = [
    'user_type',
    'participant_first_name',
    'participant_last_name',
    'participant_email',
    'total_questions',
    'total_correct_answers',
    'score_percentage',
    'reward_coupon_code',
    'submitted_at',
];
```

## Seeder Changes

**File**: `database/seeders/QuizeSeeder.php`

- **Question 1**: `user_type = 'common'` (kit selection automation - shown to all users)
- **Question 2**: `user_type = 'doctor'` (storage temperature)
- **Question 3**: `user_type = 'doctor'` (compliance standards)
- **Question 4**: `user_type = 'doctor'` (sample preparation)

All data is **100% hardcoded** with no conditional logic.

## Service Layer Changes

**File**: `app/Services/Quize/QuizeService.php`

### Method 1: quizQuestions()
Loads only common questions for initial page display.
```php
public function quizQuestions(): Collection
{
    // Filters: WHERE user_type = 'common'
}
```

### Method 2: quizQuestionsByUserType()
Loads common questions plus user-type-specific questions.
```php
public function quizQuestionsByUserType(string $selectedUserType): Collection
{
    // Filters: WHERE user_type IN ['common', $selectedUserType]
    // This ensures all users get common questions + their specific ones
}
```

### Method 3: createQuizResponse()
Updated to:
- Accept `user_type` from validated input
- Filter questions using `quizQuestionsByUserType()`
- Store `user_type` with the response record
- Log user_type for audit trail

## Form Request Changes

**File**: `app/Http/Requests/Quize/StoreQuizResponseRequest.php`

Added validation rule for user_type:
```php
'user_type' => ['required', 'string', 'in:common,doctor,lab,dealer,other'],
```

This ensures only valid user types are accepted from the frontend.

## API Integration

### Form Submission Expected Structure
When the frontend submits the form, include:
```json
{
    "user_type": "doctor",
    "participant_first_name": "John",
    "participant_last_name": "Doe",
    "participant_email": "john@example.com",
    "selected_answers": {
        "1": 1,
        "2": 5,
        "3": 10,
        "4": 14
    }
}
```

### Response Record
The response is now saved with:
- `user_type`: The selected user type
- `participant_*`: User contact information
- `total_questions`: Count of questions shown (varies by user type)
- `score_percentage`: Calculated score
- Other audit fields

## Data Flow (Backend Perspective)

### On Page Load
1. `QuizeController::index()` called
2. Service loads `quizQuestions()` (common only)
3. Blade template renders common question
4. UI handles role selection (hardcoded options: doctor, lab, dealer, other)

### On Form Submission
1. User selects role in Step 1
2. User completes Step 2 (role-specific form)
3. Steps 3-5: UI loads backend-driven questions via form fields or AJAX
4. User submits: `POST /diagnostic-quiz`
5. `StoreQuizResponseRequest` validates including user_type
6. `QuizeController::store()` calls service
7. Service gets user_type from input
8. Service loads questions via `quizQuestionsByUserType(user_type)`
9. Service validates all required questions answered
10. Service calculates score
11. Service saves response WITH user_type
12. Response is returned to frontend

## Important Notes

1. **First question is still common** - All users see Question 1 (role selection/kit selection) to maintain assessment consistency baseline
2. **Questions 2-4 are user-specific** - Different users see different follow-up questions
3. **No UI changes made** - UI look and feel remains unchanged; only backend behavior updated
4. **Hardcoded seeder data** - All question data is hardcoded with no business logic in seeder
5. **Simple direct flow** - Single question loading per user type, no clever abstractions
6. **Raw data to UI** - Backend passes unformatted data; UI responsible for presentation

## Rollback / Testing Steps

```bash
# 1. Review migration changes
php artisan migrate:status

# 2. Run seeder
php artisan db:seed --class=QuizeSeeder

# 3. Test API
POST /diagnostic-quiz
{
    "user_type": "doctor",
    "participant_first_name": "Test",
    "participant_email": "test@example.com",
    "selected_answers": {
        "1": 1,
        "2": 5,
        "3": 10,
        "4": 14
    }
}

# 4. Verify response saves with user_type
SELECT user_type, COUNT(*) FROM diagnostic_quiz_responses GROUP BY user_type;
```

## Files Modified Summary

| File | Type | Change |
|------|------|--------|
| `database/migrations/2026_03_24_000300_create_diagnostic_quiz_tables.php` | Schema | Added user_type columns and indexes |
| `app/Models/Quize/QuizeQuestion.php` | Model | Added user_type to fillable |
| `app/Models/Quize/QuizeResponse.php` | Model | Added user_type to fillable |
| `database/seeders/QuizeSeeder.php` | Seeder | Added user_type to question records |
| `app/Services/Quize/QuizeService.php` | Service | Added filtering methods, updated createQuizResponse |
| `app/Http/Requests/Quize/StoreQuizResponseRequest.php` | Request | Added user_type validation |

No changes were made to:
- QuizeController (already works correctly)
- Views/Blade files (UI unchanged)
- Routes configuration
