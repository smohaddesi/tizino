# Database Structure

## Question Bank

Grade
 └── Subject
      └── Topic
           └── Question
                └── QuestionOption

## Exam System

Exam
 ├── ExamQuestion
 └── ExamAttempt
       └── AttemptAnswer

## Notes

- هر Question متعلق به یک Topic است.
- هر Topic متعلق به یک Subject است.
- هر Subject متعلق به یک Grade است.

Tables completed:

- grades
- subjects

Relations:

grades
    └── hasMany(subjects)

subjects
    └── belongsTo(grades)