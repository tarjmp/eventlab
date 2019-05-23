function addChatMessage() {

    // the form containing all the input data
    let form = $('#msg-form');
    let input = $('#message');

    // do not send empty messages
    if (input.val().trim() === '') {
        return false;
    }

    // the url where the post request must go
    let url = form.attr('action');

    // collect required data for the post request
    let formData = form.serialize();

    // send post request
    $.post(url, formData, (data, status) => {
        // handle successful request
        if (status === 'success') {

            let msgCount = $('#msg-count');
            let messages = $('#messages');

            // append chat messages
            messages.append(data);

            // update message counter in the task bar
            let numNewMsg = data.split('<!--#MSG#-->').length - 1;
            let numOldMsg = parseInt(msgCount.text());

            msgCount.text(numOldMsg + numNewMsg);

            scrollToBottom();

            // clear input field and focus it
            input.val('').focus();
        }
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