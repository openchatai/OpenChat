import os
from web.views.views_chatbot import check_authentication
from django.shortcuts import render, redirect, get_object_or_404
from django.core.exceptions import ValidationError
from django.http import HttpResponse, Http404
from django.db.models import Count, Min
from django.http import HttpResponseNotFound, FileResponse, HttpResponseRedirect
from django.conf import settings
from django.core.files.storage import default_storage
from django.shortcuts import render
from django.contrib.auth.decorators import login_required
from django.views.decorators.csrf import csrf_exempt
from django.contrib.auth.models import User
from django.contrib.auth.hashers import make_password
from django.utils.timezone import now
from django.utils.crypto import get_random_string
from django.urls import reverse
from django.contrib import messages

@login_required(login_url='/login/')
@check_authentication
@csrf_exempt
def createuser(request):
    if not request.user.is_superuser:
        return HttpResponseRedirect(reverse('index'))
    if request.method == 'POST':
        username = request.POST.get('username')
        first_name = request.POST.get('first_name')
        last_name = request.POST.get('last_name')
        email = request.POST.get('email')
        password = request.POST.get('password')
        check_password = request.POST.get('check_password')

        # Generate a suggested password for the form
        suggested_password = get_random_string(length=12)

        # Initialize an error message list
        error_messages = []

        # Check if the username already exists
        if User.objects.filter(username=username).exists():
            error_messages.append('Username already exists. Please choose a different username.')

        # Check if the username is provided
        if not username:
            error_messages.append('Username is required.')

        # Check if the password is provided
        if not password:
            error_messages.append('Password is required.')
        
        # Check if the email is provided
        if not email:
            error_messages.append('Email is required.')

        # Check if the password and check_password match
        if password and check_password and password != check_password:
            error_messages.append('Passwords do not match.')

        # If there are any error messages, re-render the form with the errors
        if error_messages:
            context = {
                'username': username,
                'first_name': first_name,
                'last_name': last_name,
                'email': email,
                'SUGGESTEDPASS': suggested_password,
                'error_messages': error_messages,
            }
            return render(request, 'create_user.html', context)

        # Generate a hashed password
        hashed_password = make_password(password)

        # Create the user
        user = User.objects.create(
            username=username,
            first_name=first_name,
            last_name=last_name,
            email=email,
            password=hashed_password,
            is_superuser=False,
            is_staff=False,
            is_active=True,
            date_joined=now()
        )

        # Redirect to a new URL:
        request.session['created_username'] = username  # Store the username in the session
        return redirect('createuser_success')

    else:
        # Render the empty form for user creation
        suggested_password = get_random_string(length=12)
        return render(request, 'create_user.html', {'SUGGESTEDPASS': suggested_password})


@login_required(login_url='/login/')
@check_authentication
def createuser_success(request):
    username = request.session.get('created_username', '')
    request.session.pop('created_username', None)  # Clear the username from the session after retrieving it
    return render(request, 'create_user.html', {
        'success_message': 'User created successfully!',
        'created_username': username
    })


@check_authentication
def modify_user(request):
    # Generate a suggested password for the form
    suggested_password = get_random_string(length=12)

    if request.method == 'POST':
        first_name = request.POST.get('first_name')
        last_name = request.POST.get('last_name')
        email = request.POST.get('email')
        password = request.POST.get('password')
        check_password = request.POST.get('check_password')

        # Initialize an error message list
        error_messages = []
        
        # Check if the email is provided
        if not email:
            error_messages.append('Email is required.')

        # Check if the password and check_password match
        if password and check_password and password != check_password:
            error_messages.append('Passwords do not match.')

        # If there are any error messages, re-render the form with the errors
        if error_messages:
            context = {
                'first_name': first_name,
                'last_name': last_name,
                'email': email,
                'SUGGESTEDPASS': suggested_password,
                'error_messages': error_messages,
            }
            return render(request, 'modify_user.html', context)
        # After successful modification, redirect to the index page
        # return HttpResponseRedirect(reverse('index'))
        
        # Generate a hashed password
        hashed_password = make_password(password)

        # Update the user
        user = request.user
        user.first_name = first_name
        user.last_name = last_name
        user.email = email
        if password:
            hashed_password = make_password(password)
            user.password = hashed_password
        user.save()

        # messages.success(request, 'Your profile was updated successfully!')
        return HttpResponseRedirect(reverse('index'))

    else:
        # Render the modify_user.html template with the current user data
        return render(request, 'modify_user.html', {'user': request.user, 'SUGGESTEDPASS': suggested_password})
