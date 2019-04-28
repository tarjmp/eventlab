<?php


class SeedConstants
{
    // +++ EVENT REPLIES +++
    // array with the relation between replies between an event and a user
    public const EVENT_REPLIES = [[1, 2], [4, 5], [3, 8], [2, 4], [7, 3], [8, 10], [6, 4], [9, 7], [5, 2], [10, 2]];

    // array with the position in table where a reply was accepted, rejected or tentative
    public const EVENT_TENTATIVE = [3, 5, 6, 8, 9];
    public const EVENT_ACCEPTED  = [1, 4, 7];
    public const EVENT_REJECTED  = [2, 10];


    // +++ EVENTS +++
    // array containing the group and user (creator) of each event
    // (array index + 1 = event id)
    public const EVENT_CREATED_BY = [[2, 2], [3, 4], [6, 8], [NULL, 5], [2, 2], [3, 4], [9, 3], [NULL, 10], [NULL, 7], [2, 2]];

    // array containing the all day events
    public const EVENT_ALL_DAY = [1, 4, 6, 7, 8, 10];

    // +++ GROUPS +++
    // arrays with the temporary and public groups
    // CAUTION: temporary groups must never be public!
    public const GROUPS_TEMPORARY = [1, 4, 5, 8];
    public const GROUPS_PUBLIC = [2, 6, 7, 9];

    // +++ GROUP-USER +++
    // array with memberships and subscriptions towards a group (group id + user id)
    public const SUBSCRIPTIONS = [[1, 10], [4, 6], [5, 7], [7, 1], [8, 5], [10, 9]];
    public const MEMBERSHIPS = [[2, 2], [3, 4], [6, 8], [9, 3]];

    // +++ ITEMS +++
    // pairs of event id and user id
    public const ITEMS = [[7, 3], [8, 10], [1, 2], [4, 5], [10, 2], [5, 2], [9, 7], [6, 4], [3, 8], [2, 4]];

    // +++ MESSAGES +++
    // array containing the event and user for each message
    public const MESSAGES = [[5, 2], [3, 8], [10, 2], [8, 10], [7, 3], [2, 4], [9, 7], [6, 4], [1, 2], [4, 5]];


}