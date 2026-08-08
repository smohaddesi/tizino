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
- هر ExamQuestion به یک Exam و یک Question متصل است.
- هر ExamAttempt متعلق به یک Exam و یک User است.
- SchoolClass فعلاً بدون رابطه و خارج از جریان اصلی بانک سؤال است (تصمیم معماری).

## Implemented Tables

- users
- cache
- jobs
- grades
- subjects
- topics
- questions
- question_options
- school_classes
- exams
- exam_questions
- exam_attempts
- attempt_answers

## Relationships

Grade
└── hasMany → Subject

Subject
└── belongsTo → Grade
└── hasMany → Topic

Topic
└── belongsTo → Subject
└── hasMany → Question

Question
└── belongsTo → Topic
└── hasMany → QuestionOption
└── hasMany → ExamQuestion

Exam
└── belongsTo → Grade
└── hasMany → ExamQuestion
└── hasMany → ExamAttempt

ExamQuestion
└── belongsTo → Exam
└── belongsTo → Question
└── hasMany → AttemptAnswer

ExamAttempt
└── belongsTo → Exam
└── belongsTo → User
└── hasMany → AttemptAnswer