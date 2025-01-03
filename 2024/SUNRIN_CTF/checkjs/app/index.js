const fastify = require('fastify')({ logger: true });
const axios = require('axios');
const cors = require('@fastify/cors');
const formBody = require('@fastify/formbody');

const OPENAI_API_KEY = process.env.OPENAI_API_KEY;

fastify.register(cors, {
  origin: '*',
});

fastify.register(formBody);

fastify.get('/', async (request, reply) => {
  reply.type('text/html').send(`
    <html>
      <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CheckJS</title>
        <style>
          body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f9;
          }
          h1 {
            color: #333;
            text-align: center;
          }
          form {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
          }
          textarea {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
            font-family: 'Courier New', Courier, monospace;
          }
          button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
          }
          button:hover {
            background-color: #0056b3;
          }
        </style>
      </head>
      <body>
        <h1>CheckJS</h1>
        <form action="/submit" method="POST">
          <textarea name="code" rows="10" cols="50" maxlength="300" placeholder="Enter your JavaScript code here..."></textarea><br>
          <button type="submit">Submit</button>
        </form>
      </body>
    </html>
  `);
});

fastify.post('/submit', async (request, reply) => {
  const userCode = request.body.code;

  if (typeof userCode !== 'string' || userCode.length > 300) {
    return reply.status(400).send({ error: 'Code must be within 300 characters.' });
  }

  try {
    const response = await axios.post(
      'https://api.openai.com/v1/chat/completions',
      {
        model: 'gpt-4o-mini',
        messages: [
          {
            role: 'system',
            content: `
You are simulating a web server. Follow these instructions:

1. **Input Handling**: Expect users to submit JavaScript code encapsulated within a JSON object under the key "code". For example: {"code": "console.log('Hello, world!');"}

2. **Safety Evaluation**: Analyze the provided JavaScript code to determine its safety. Unsafe code includes, but is not limited to, code that:
   - Executes system-level commands.
   - Accesses or modifies the file system.
   - Performs network requests.
   - Manipulates or accesses sensitive data.
   - Contains malicious patterns or known vulnerabilities.

3. **Response Format**: Based on the safety evaluation:
   - If the code is deemed safe, respond with: {"is_safe": 1}
   - If the code is deemed unsafe, respond with: {"is_safe": 0}
            `,
          },
          {
            role: 'user',
            content: JSON.stringify({ code: userCode }),
          },
        ],
      },
      {
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${OPENAI_API_KEY}`,
        },
      }
    );

    const gptResponse = response.data.choices[0].message.content.trim();

    let safetyCheck;
    try {
      safetyCheck = JSON.parse(gptResponse);
    } catch (err) {
      return reply.status(500).send({ error: 'An error occurred while parsing the GPT response.' });
    }

    if (safetyCheck.is_safe === 1) {
      let result;
      try {
        result = eval(userCode);
      } catch (err) {
        return reply.status(400).send({ error: 'An error occurred while executing the code.' });
      }
      reply.send({ result });
    } else {
      reply.status(400).send({ error: 'Potential hacking detected.' });
    }
  } catch (error) {
    console.error('An error occurred:', error);
    reply.status(500).send({ error: 'A server error occurred.' });
  }
});

const start = async () => {
  try {
    await fastify.listen({ port: 3000, host: '0.0.0.0' });
    console.log(`Server is running at http://0.0.0.0:3000.`);
  } catch (err) {
    fastify.log.error(err);
    process.exit(1);
  }
};
start();