export default () => ({
  title_prompt: null,
  prompt_summary: null,
  prompt_content: null,
  prompt_image: 'Developer in a dark room with a purple and blue backlight, multi monitor setup with nice ui, 3D render.',
  cover_image_path: null,
  loading: false,
  aiGenerate(type, title, summary) {
    this.loading = !this.loading

    fetch(`ai/blog`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content')
      },
      body: JSON.stringify({
        type: type,
        title_prompt: this.title_prompt,
        summary: this.prompt_summary,
        content: this.prompt_content,
        cover_image: this.prompt_image

      })
    }).then(response => {
      console.log(response);
      return response.json()
    }).then(data => {
      if (data.success) {
        this.loading = false
        console.log(data)
        if(type === 'image'){
          this.cover_image_path = data.body
        }
        if(type === 'title') {
          this.title_prompt = data.body
        }
        if(type === 'summary') {
          this.title_prompt = title
          this.prompt_summary = data.body
        }
        if(type === 'content'){
          this.title_prompt = title
          this.prompt_summary = summary
          this.prompt_content = data.body
        }
      }
    }).catch(error => {
      console.error(error);
    })

  }

})
