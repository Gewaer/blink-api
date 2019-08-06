<?php

declare(strict_types=1);

namespace Gewaer\Mapper;

use AutoMapperPlus\CustomMapper\CustomMapper;
use Canvas\Mapper\UserMapper as CanvasUserMapper;
use Gewaer\Models\Users;
use Canvas\Contracts\Mapper\RelationshipTrait;

class UserMapper extends CanvasUserMapper
{
    use RelationshipTrait;

    /**
     * @param Users $user
     * @param Canvas\Dto\User $userDto
     * @return Canvas\Dto\User
     */
    public function mapToObject($user, $userDto, array $context = [])
    {
        if (is_array($user)) {
            $user = (object) $user;
        }

        $userDto->id = $user->id;
        $userDto->active_subscription_id = $user->active_subscription_id;
        $userDto->card_brand = $user->card_brand;
        $userDto->cell_phone_number = $user->cell_phone_number;
        $userDto->city_id = $user->city_id;
        $userDto->country_id = $user->country_id;
        $userDto->created_at = $user->created_at;
        $userDto->default_company = Users::getById($user->id)->getDefaultCompany()->getId();
        $userDto->default_company_branch = $user->default_company_branch;
        $userDto->displayname = $user->displayname;
        $userDto->dob = $user->dob;
        $userDto->email = $user->email;
        $userDto->firstname = $user->firstname;
        $userDto->interest = $user->interest;
        $userDto->karma = $user->karma;
        $userDto->language = $user->language;
        $userDto->lastname = $user->lastname;
        $userDto->lastvisit = $user->lastvisit;
        $userDto->location = $user->location;
        $userDto->phone_number = $user->phone_number;
        $userDto->profile_header = $user->profile_header;
        $userDto->profile_header_mobile = $user->profile_header_mobile;
        $userDto->profile_image = $user->profile_image;
        $userDto->profile_image_mobile = $user->profile_image_mobile;
        $userDto->profile_image_thumb = $user->profile_image_thumb;
        $userDto->profile_privacy = $user->profile_privacy;
        $userDto->profile_remote_image = $user->profile_remote_image;
        $userDto->registered = $user->registered;
        $userDto->roles_id = $user->roles_id;
        $userDto->session_id = $user->session_id;
        $userDto->session_key = $user->session_key;
        $userDto->session_page = $user->session_page;
        $userDto->session_time = $user->session_time;
        $userDto->sex = $user->sex;
        $userDto->state_id = $user->state_id;
        $userDto->status = $user->status;
        $userDto->stripe_id = $user->stripe_id;
        $userDto->system_modules_id = $user->system_modules_id;
        $userDto->timezone = $user->timezone;
        $userDto->trial_ends_at = $user->trial_ends_at;
        $userDto->updated_at = $user->updated_at;
        $userDto->user_active = $user->user_active;
        $userDto->user_last_login_try = $user->user_last_login_try;
        $userDto->user_level = $user->user_level;
        $userDto->user_login_tries = $user->user_login_tries;
        $userDto->votes = $user->votes;
        $userDto->votes_points = $user->votes_points;
        $userDto->welcome = $user->welcome;
        $userDto->photo = $user->photo;
        $userDto->followed_tags = $user->getTags(['columns' => 'id, title, slug']);

        $this->getRelationships($user, $userDto, $context);

        if (!empty($userDto->roles)) {
            if (isset($userDto->roles[0])) {
                $this->accesList($userDto);
            }
        }

        return $userDto;
    }
}
