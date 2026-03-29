class Todo {
    constructor() {
        const saved = localStorage.getItem('tasks');
          if (saved) {
              this.tasks = JSON.parse(saved);
          }
          else {
              this.tasks = [];
          }

        this.nextId = 1;
          this.tasks.forEach(task => {
              if (task.id >= this.nextId) {
                  this.nextId = task.id + 1;
            }
        });

        this.term = '';
    }

  add(text, date){
      const task = {
          id: this.nextId,
          text: text,
          date: date
      };
      this.tasks.push(task);
      this.nextId++;

      this.save()
  }

  draw() {
      const ul = document.getElementById('tasks-container');
      ul.innerHTML = '';

      this.filterTasks().forEach(task => {
          const li = document.createElement('li');

          let displayText = task.text;

          if (this.term.length >= 2) {
              displayText = task.text.replaceAll(
                  this.term,
                  `<mark>${this.term}</mark>`
              );
          }

          li.innerHTML = `<span>${displayText}</span>
                            ${task.date ? `<small>${task.date}</small>` : ''}
                            <button class="delete-btn">X</button>`;
          ul.appendChild(li);

          const btn = li.querySelector('.delete-btn');
          btn.addEventListener('click', () => {
              this.remove(task.id);
          });

          const span = li.querySelector('span');
          span.addEventListener('click', () => {
              const inputText = document.createElement('input');
              inputText.type = 'text';
              inputText.value = task.text;

              const inputDate = document.createElement('input');
              inputDate.type = 'date';
              inputDate.value = task.date;

              li.replaceChild(inputText, span);
              li.insertBefore(inputDate, btn);
              inputText.focus();

              li.addEventListener('focusout', (e) => {
                  if (!li.contains(e.relatedTarget)) {
                      this.edit(task.id, inputText.value, inputDate.value);
                  }
              });
          });
    });
  }

  remove(id) {
      this.tasks = this.tasks.filter(task => task.id !== id);
      this.draw();

      this.save()
  }

  edit(id, newText, newDate) {
      const task = this.tasks.find(task => task.id === id);
      task.text = newText;
      task.date = newDate;

      this.draw();
      this.save()
  }

  save()  {
      localStorage.setItem('tasks', JSON.stringify(this.tasks));
  }

  filterTasks()  {
      if (this.term.length < 2) {
          return this.tasks;
      }

      return this.tasks.filter(task =>
          task.text.toLowerCase().includes(this.term.toLowerCase())
      );
  }
}

document.todo = new Todo();

document.getElementById('add-btn').addEventListener('click', () => {
    const text = document.getElementById('add-box').value;
    const date = document.getElementById('date-box').value;

    if (text.length < 3) {
        alert('Co najmniej 3 znaki');
        return;
    }

    if (text.length > 255) {
        alert('Nie więcej niż 255 znaków');
        return;
    }

      if (date !== '' && new Date(date) < new Date()) {
        alert('Data pusta lub w przyszłośći');
        return;
    }

    document.todo.add(text, date);
    document.todo.draw();
});

document.todo.draw();

document.getElementById('search-box').addEventListener('input', (e) => {
    document.todo.term = e.target.value;
    document.todo.draw();
});
