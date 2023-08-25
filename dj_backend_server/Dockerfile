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

# Set environment variables for the command
ENV CMAKE_ARGS="-DLLAMA_CUBLAS=on" \
    FORCE_CMAKE=1

# Update pip and install the required package
RUN pip install --upgrade pip && \
    pip install --no-cache-dir llama-cpp-python

# Run migrations on startup
CMD ["sh", "-c", "python manage.py sync_models && python manage.py runserver 0.0.0.0:8000"]