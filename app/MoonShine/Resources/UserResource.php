<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\MenuManager\Attributes\Group;
use MoonShine\Support\Attributes\Icon;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Password;
use MoonShine\UI\Fields\PasswordRepeat;
use MoonShine\UI\Fields\Text;

#[Icon('user-circle')]
#[Group('Журналы атлетов')]
/**
 * @extends ModelResource<User>
 */
class UserResource extends ModelResource
{
    protected string $model = User::class;

    protected string $column = 'name';

    public function title(): string
    {
        return 'Атлеты';
    }

    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Имя', 'name')->sortable(),
            Email::make('E-mail', 'email')->sortable(),
            Date::make('Создан', 'created_at')->format('d.m.Y'),
        ];
    }

    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Text::make('Имя', 'name')->required(),
                Email::make('E-mail', 'email')->required(),
                Password::make('Пароль', 'password')->eye()->customAttributes(['autocomplete' => 'new-password']),
                PasswordRepeat::make('Подтверждение пароля', 'password_confirmation')->eye(),
            ]),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function rules($item): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'unique:users,email,' . $item->getKey()],
            'password' => [$item->exists ? 'nullable' : 'required', 'string', 'min:6', 'confirmed'],
        ];
    }

    protected function search(): array
    {
        return ['name', 'email'];
    }
}
