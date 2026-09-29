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
- `questions.is_free_sample` (boolean, پیش‌فرض false) — سؤال بدون نیاز به اشتراک فعال هم در بانک سؤال دانش‌آموزی قابل مشاهده‌ست.
- `questions.answer_time` (smallint unsigned, **nullable**، بدون پیش‌فرض) — زمان پیشنهادی پاسخ به ثانیه؛ اختیاری (سؤال بانک‌محض نیازی بهش نداره). قبلاً NOT NULL با پیش‌فرض ۷۵ بود؛ سؤال‌های قدیمی همون مقدار قبلی رو نگه می‌دارن.
- `questions.answer_explanation_image` (string, nullable) — مسیر تصویر پاسخ تشریحی سؤال، بعد از `answer_explanation`.
- `users.avatar` (string, nullable) — مسیر فایل عکس پروفایل روی دیسک `public` (نیازمند `php artisan storage:link`).

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