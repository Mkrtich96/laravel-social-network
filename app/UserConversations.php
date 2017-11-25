<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserConversations extends Pivot
{

    protected $table = 'conversations_users';

}
