<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $token
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InpolToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InpolToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InpolToken query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InpolToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InpolToken whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InpolToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InpolToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InpolToken whereUpdatedAt($value)
 */
	class InpolToken extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property int $status
 * @property string|null $signature
 * @property string $type_id
 * @property array<array-key, mixed> $type_names
 * @property string $person
 * @property \Illuminate\Support\Carbon $creation_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeopleCase newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeopleCase newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeopleCase query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeopleCase whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeopleCase whereCreationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeopleCase whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeopleCase wherePerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeopleCase whereSignature($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeopleCase whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeopleCase whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeopleCase whereTypeNames($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeopleCase whereUpdatedAt($value)
 */
	class PeopleCase extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

