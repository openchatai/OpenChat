# Use an official Python runtime as a parent image
FROM python:3.9

# Set environment variables for the project
ENV PYTHONUNBUFFERED 1
ENV DJANGO_SETTINGS_MODULE=dj_backend_server.settings

# Set the working directory to /app
WORKDIR /app

# Copy the current directory contents into the container at /app
COPY . /app/

# Install any needed packages specified in requirements.txt
RUN pip install --no-cache-dir -r requirements.txt

# Run migrations on startup
CMD ["sh", "-c", "python manage.py migrate && python manage.py runserver 0.0.0.0:8000"]
