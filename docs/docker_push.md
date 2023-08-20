docker login -u codebanesr

<!-- llm-server -->
docker build -t codebanesr/openchat_llm_server:tagname .
docker push codebanesr/openchat_llm_server:tagname


<!-- backend server -->
docker build -t codebanesr/openchat_backend_server:latest .

docker push codebanesr/openchat_backend_server:latest