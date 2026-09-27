<?php

namespace App\Filament\Widgets;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Grade;
use App\Models\Payment;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Topic;
use App\Models\User;
use Filament\Widgets\Widget;

class DashboardTilesWidget extends Widget
{
    protected string $view = 'filament.widgets.dashboard-tiles-widget';

    protected int|string|array $columnSpan = 'full';

    public function getTileGroups(): array
    {
        return [
            [
                'label' => 'سیستم آزمون',
                'tiles' => [
                    [
                        'label' => 'آزمون‌ها',
                        'count' => Exam::count(),
                        'url' => route('filament.admin.resources.exams.index'),
                        'icon' => 'heroicon-o-clipboard-document-list',
                        'card' => 'bg-amber-500 hover:bg-amber-600',
                    ],
                    [
                        'label' => 'نتایج آزمون‌ها',
                        'count' => ExamAttempt::count(),
                        'url' => route('filament.admin.resources.exam-attempts.index'),
                        'icon' => 'heroicon-o-chart-bar',
                        'card' => 'bg-orange-500 hover:bg-orange-600',
                    ],
                ],
            ],
            [
                'label' => 'بانک سؤال',
                'tiles' => [
                    [
                        'label' => 'پایه‌ها',
                        'count' => Grade::count(),
                        'url' => route('filament.admin.resources.grades.index'),
                        'icon' => 'heroicon-o-academic-cap',
                        'card' => 'bg-emerald-500 hover:bg-emerald-600',
                    ],
                    [
                        'label' => 'درس‌ها',
                        'count' => Subject::count(),
                        'url' => route('filament.admin.resources.subjects.index'),
                        'icon' => 'heroicon-o-book-open',
                        'card' => 'bg-teal-500 hover:bg-teal-600',
                    ],
                    [
                        'label' => 'موضوعات',
                        'count' => Topic::count(),
                        'url' => route('filament.admin.resources.topics.index'),
                        'icon' => 'heroicon-o-rectangle-stack',
                        'card' => 'bg-cyan-600 hover:bg-cyan-700',
                    ],
                    [
                        'label' => 'سؤال‌ها',
                        'count' => Question::count(),
                        'url' => route('filament.admin.resources.questions.index'),
                        'icon' => 'heroicon-o-question-mark-circle',
                        'card' => 'bg-sky-600 hover:bg-sky-700',
                    ],
                ],
            ],
            [
                'label' => 'اشتراک و پرداخت',
                'tiles' => [
                    [
                        'label' => 'پلن‌های اشتراک',
                        'count' => SubscriptionPlan::count(),
                        'url' => route('filament.admin.resources.subscription-plans.index'),
                        'icon' => 'heroicon-o-credit-card',
                        'card' => 'bg-violet-500 hover:bg-violet-600',
                    ],
                    [
                        'label' => 'اشتراک‌های کاربران',
                        'count' => Subscription::count(),
                        'url' => route('filament.admin.resources.subscriptions.index'),
                        'icon' => 'heroicon-o-shield-check',
                        'card' => 'bg-purple-600 hover:bg-purple-700',
                    ],
                    [
                        'label' => 'تراکنش‌های پرداخت',
                        'count' => Payment::count(),
                        'url' => route('filament.admin.resources.payments.index'),
                        'icon' => 'heroicon-o-banknotes',
                        'card' => 'bg-pink-600 hover:bg-pink-700',
                    ],
                ],
            ],
            [
                'label' => 'مدیریت کاربران',
                'tiles' => [
                    [
                        'label' => 'کاربران',
                        'count' => User::count(),
                        'url' => route('filament.admin.resources.users.index'),
                        'icon' => 'heroicon-o-users',
                        'card' => 'bg-rose-600 hover:bg-rose-700',
                    ],
                ],
            ],
        ];
    }
}
