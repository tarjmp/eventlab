<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Basic extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //  USERS
        //      - contains all personal information about users of the application
        // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

        Schema::create('users', function (Blueprint $table) {

            // user id, primary key
            $table->increments('id');

            // email and password, required for login
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // personal data
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth')->nullable();
            $table->string('location')->nullable();

            // timezone, needed for displaying the time accordingly
            $table->string('timezone')->default('Europe/Berlin');

            // default fields for user login
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //  GROUPS
        //      - groups of multiple users; this includes public, private and temporary groups
        // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        Schema::create('groups', function (Blueprint $table) {

            // group id, primary key
            $table->increments('id');

            // group name; optional as it is not required for temporary groups
            $table->string('name')->nullable();
            $table->string('description')->nullable();

            // flag for temporary groups
            // temporary groups are created automatically if the user invites several users to an event and not a certain group
            $table->boolean('temporary')->default(false);

            // flag for public groups (organizations) that are visible to everyone
            $table->boolean('public')->default(false);

            // default timestamps for update and create actions
            $table->timestamps();
        });

        // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //  GROUP_USER
        //      - memberships and subscriptions to any groups
        // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        Schema::create('group_user', function (Blueprint $table) {

            // primary key, n:m relationship between users and groups
            $table->unsignedInteger('group_id');
            $table->unsignedInteger('user_id');
            $table->primary(['group_id', 'user_id']);
            $table->foreign('group_id')->references('id')->on('groups');
            $table->foreign('user_id')->references('id')->on('users');

            // status flag, this determines whether the user is a full member of the group or a subscriber
            $table->enum('status', ['membership', 'subscription']);

            $table->timestamps();
        });


        // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //  EVENTS
        //      -  information about all ongoing events and their date, location, people, etc.
        // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        Schema::create('events', function (Blueprint $table) {

            // event id, primary key
            $table->increments('id');

            // name and optional description and location of the event
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('location')->nullable();

            // start and end timestamps, boolean for all-day event
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->boolean('all_day')->default(false);

            // foreign key to the corresponding group; null for private single-person events
            $table->unsignedInteger('group_id')->nullable();
            $table->foreign('group_id')->references('id')->on('groups');

            // creator of this event
            $table->unsignedInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');

            // default timestamps for update and create actions
            $table->timestamps();
        });

        // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //  EVENT_REPLIES
        //      - information about who will come to the event
        // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        Schema::create('event_replies', function (Blueprint $table) {

            // primary key: user_id and event_id
            $table->unsignedInteger('event_id');
            $table->unsignedInteger('user_id');
            $table->primary(['event_id', 'user_id']);
            $table->foreign('event_id')->references('id')->on('events');
            $table->foreign('user_id')->references('id')->on('users');

            // reply status: accepted / rejected / tentative
            $table->enum('status', ['accepted', 'rejected', 'tentative']);

            // default create and update timestamps
            $table->timestamps();
        });

        // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //  MESSAGES
        //      - contains all chat messages for the events
        // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        Schema::create('messages', function (Blueprint $table) {

            // message id, primary key
            $table->increments('id');

            // the actual message content
            $table->string('text');

            // event the chat message is for and corresponding user
            $table->unsignedInteger('event_id');
            $table->unsignedInteger('user_id');

            $table->foreign('event_id')->references('id')->on('events');
            $table->foreign('user_id')->references('id')->on('users');

            // information about creation date; created_at
            $table->timestamps();
        });

        // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //  ITEMS
        //      - contains all what-to-bring-items for the events
        // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        Schema::create('items', function (Blueprint $table) {

            // item id
            $table->increments('id');

            // name and amount of the item (amount can be a string, as this does not necessarily need to be a number)
            $table->string('name');
            $table->string('amount')->nullable();

            // event and corresponding user that will bring the item
            $table->unsignedInteger('event_id');
            $table->unsignedInteger('user_id');

            $table->foreign('event_id')->references('id')->on('events');
            $table->foreign('user_id')->references('id')->on('users');

            // information about creation date; created_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('items');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('event_replies');
        Schema::dropIfExists('events');
        Schema::dropIfExists('group_user');
        Schema::dropIfExists('groups');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('users');
    }
}
