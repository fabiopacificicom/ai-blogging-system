<?php

return [
  'openai' => [
    'api_key' => env('OPENAI_API_KEY', ''),
    'endpoints' => [
      'chat' => [
        'completations' => 'https://api.openai.com/v1/chat/completions',
      ],
      'images' => [
        'create' => 'https://api.openai.com/v1/images/generations',
      ]
    ],
  ],
  'presets' => [
    'system' => [
      'role' => 'system',
      'content' => "You are an experienced Senior Full Stack developer. You are friendly and write clean and well documented code in short step by step blog posts with code snippets. Use git flavoured markdown for code snippets. For example: ```bash\ncomposer create-project --prefer-dist laravel/laravel rest-api```. Add reference links at the end of the article so that users can dig deeper into the technologies used but point out that links might be outdated as technology evolves quickly. Use emoji to make your posts more engaging.\n'",
    ],
    'shareInstructions' => [
      'role' => 'user',
      'content' => "Use the post summary below to generate a social network post in English and Italian to promote my latest blog post. Hashtags: #ethicalwebdeveloper #fullstack #programming #ai.\nBlog Post Summary:\n",
    ],
    'share' => [
      'max_tokens' => 550,
      'temperature' => 0.2,
    ],

    'blog' => [
      'topics' => [
        'Full stack', 'Web Development', 'REST API', 'Laravel Development', 'Writing tests (Pest, cypress, jest, mocha)', 'PHP', 'SQL', 'HTML', 'JS', 'CSS', 'SCSS', 'Vuejs', 'alpine.js', 'AI in web development', 'Code like a jedi master', 'Inertia.js', 'Docker', 'Kubernetes', 'svelte.js', 'websocket', 'Web components', 'Advanced Laravel features such as Queues, Events, Broadcasting, and Horizon.', 'Building scalable and high-performance APIs with Laravel.', 'Advanced Vue.js features such as Vuex, Vue Router, and Server-Side Rendering (SSR).', 'Building real-time applications with Laravel and Vue.js using technologies like Pusher or WebSockets.', 'Advanced database concepts such as database optimization, indexing, and caching.', 'Implementing advanced authentication and authorization mechanisms in Laravel.', 'Building microservices with Laravel and Vue.js.', 'Advanced testing techniques such as Test-Driven Development (TDD) and Behavior-Driven Development (BDD).', 'Implementing advanced security measures such as encryption, hashing, and SSL.', 'Building Progressive Web Applications (PWAs) with Laravel and Vue.js.', 'Web scraping and automation with Python', 'Data analysis and visualization with Python', 'Machine learning with Python libraries such as Scikit-learn, TensorFlow, and Keras', 'Natural Language Processing (NLP) with Python', 'Computer Vision with OpenCV and Python', 'IoT programming with Python and Raspberry Pi', 'Robotics programming with Python', 'Deep learning with Python', 'Big data processing with Python and Apache Spark', 'Network programming with Python', 'Dockerizing applications and deploying them to production environments', 'Building and managing containerized applications with Kubernetes', 'Implementing microservices architecture with Docker and Kubernetes', 'Scaling applications with Docker Swarm and Kubernetes', 'Building real-time multiplayer games with JavaScript and WebSockets', 'Implementing game physics and collision detection with JavaScript', 'Building game AI with JavaScript and machine learning libraries such as TensorFlow.js', 'Integrating game engines such as Phaser, Three.js, and Babylon.js into web applications', 'Building mobile games with JavaScript frameworks such as vuejs and Ionic',
      ],
      'target_audience' => 'Audience: Web Developers.',
      'max_post_length' => 2500,
      'default_model' => 'gpt-4o',
      'artworker' => 'dalle-3',
      'title' => [
        'target_audience' => 'Audience: Web Developers.',
        'prompt' => 'Given the following target audience and topic, generate a title for a blog post. Return the title as JSON object with the key title. Please return only the JSON object and nothing else.',
        'model' => 'text-davinci-002',
        'temperature' => 0.6,
        'max_tokens' => 25,
      ],
      'summary' => [
        'prompt' => "Given the following blog post, please summarize it and return a JSON object with a single key:value pair. Example: 'summary':'This blog post talks about: '. Please return only the JSON object and nothing else.\n",
        'model' => 'text-davinci-002',
        'temperature' => 0.8,
        'max_tokens' => 200,
      ],
      'content' => [
        'prompt' => "\nGiven the following post title, generate the blog post with at least two code snippets.\n\n",
        'model' => 'text-davinci-002',
        'temperature' => 1,
        'max_tokens' => 2500,
      ],
      'image' => [
        'prompt' => 'Developer in a dark room with a purple and blue backlight, multi monitor setup with nice ui, ',
        'type' => [
          '3d' => '3D render.',
          'art' => 'digital art.',
          'pixel' => 'pixel art.',
        ],
      ],

    ],
  ],

];
