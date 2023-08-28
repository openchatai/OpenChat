## LLAMA SUPPORT FOR MAC M1/M2 devices <span style="color: red;">⚠️ **Experimental Warning** ⚠️</span>

This repository offers <span style="color: #1E90FF;">LLAMA support</span> for Mac M1/M2 devices. LLAMA stands as an <span style="color: #008000;">advanced language model</span> developed by Meta. This comprehensive guide is intended to assist you in <span style="color: #FF8C00;">configuring and running LLAMA</span> on your Mac M1/M2 device by following the provided instructions. It's important to note that running LLAMA on Mac devices using Docker might not be straightforward due to <span style="color: #FF0000;">emulation constraints</span>, particularly related to accessing video drivers.

Additionally, the current embedding speeds are quite low, although it's worth noting that the model's speed does improve over time.

## Getting Started

Follow these steps to set up LLAMA support on your Mac M1/M2 device:

1. Clone this repository to your local machine.

2. In the root directory of the repository, locate the `.env` file and open it in a text editor.

3. Change the following two environment variables in the `.env` file:

   ```dotenv
   OPENAI_API_TYPE=llama2
   EMBEDDING_PROVIDER=llama2
   ```

   These variables configure LLAMA as the API type and embedding provider.

4. **Note**: Currently, the system supports only a specific combination of embedding and completion models. Future updates will provide more flexibility in choosing different models.

5. **Note**: Docker images are not supported for Mac devices due to emulation limitations. As a result, you need to run the application using a virtual environment (virtualenv) for now.



6. When working with Visual Studio Code, you have the option to leverage the debug scripts that are available to enhance your development process (you'll need to execute docker compose up -d to run the other docker containers). As another approach, you can employ the subsequent commands to initiate and halt the development server.
   - To start the development server:

     ```sh
     make dev-start
     ```

   - To stop the development server:

     ```sh
     make dev-stop
     ```

## Future Updates

We are continuously working on enhancing LLAMA support for Mac M1/M2 devices. Stay tuned for updates that will provide more options for embedding and completion models, as well as improved compatibility with different environments.

For any issues or questions, please reach out to our support team or open an issue in this repository.

Happy coding!
