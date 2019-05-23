scrollToBottom();
window.setInterval(refreshMessages, 10000);

function addChatMessage() {

    // the form containing all the input data
    let form  = $('#msg-form');
    let input = $('#message');
    let msgId = form.children('input[name="msg-id"]');

    msgId.val(getLastMessageId());

    // do not send empty messages
    if (input.val().trim() === '') {
        return false;
    }

    sendFormViaPost(form, (data) => {
        // on success: add new messages
        addNewMessages(data);
        // clear input field and focus it
        input.val('').focus();
    });

    return false;
}

function scrollToBottom() {
    location.hash = '#msg-bottom';
}

function deleteChatMessage(id) {

    // the form to be submitted
    let form = $('#msg-delete');
    let idField = form.children('input[name="id"]');

    // enter id into hidden form and submit it
    idField.val(id);
    form.submit();
}

function refreshMessages() {
    // the form to be submitted
    let form = $('#msg-refresh');
    let msgId = form.children('input[name="msg-id"]');

    // enter last message id into hidden form and submit it
    msgId.val(getLastMessageId());

    sendFormViaPost(form, addNewMessages);
}

function addNewMessages(data) {
    let msgCount = $('#msg-count');
    let messages = $('#messages');

    // update message counter in the task bar
    let numNewMsg = data.split('<!--#MSG#-->').length - 1;
    let numOldMsg = parseInt(msgCount.text());
    msgCount.text(numOldMsg + numNewMsg);

    // remove "no messages - start typing" message if it is present
    if (numOldMsg === 0) {
        messages.html('');
    }

    // append chat messages
    messages.append(data);

    scrollToBottom();
}

function getLastMessageId() {
    // retrieve index of last message
    let lastMessage = $('#messages').children().last();

    // extract numeric id - the actual id is in format "msg-id", e.g. "msg-5"
    return parseInt(lastMessage.attr('id').substring(4));
}

function sendFormViaPost(form, callback) {

    // the url where the post request must go
    let url = form.attr('action');

    // collect required data for the post request
    let formData = form.serialize();

    // send post request
    $.post(url, formData, (data, status) => {

        // handle successful request
        if (status === 'success') {
            callback(data);
        }

    });
}