def get_session_id(request, bot_id):
    cookie_name = 'chatbot_' + str(bot_id)

    session_id = request.COOKIES.get(cookie_name)
    return session_id
