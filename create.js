let listID;
let dict = {};
function createGame(words, title, tTime, callback) {
  dict['title']=title;
  dict['time']=tTime;
  dict['words'] = words;
  fetch('./create.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(dict),
  })
  .then(response => response.json())
  .then(data => {
    if (data.success === 1) {
      const responseID = data.id;
      if (callback) {
        callback(responseID, null);
      }
    } else {
      console.error('Server error');
      if (callback) {
        callback(null, 'Server error');
      }
    }
  })
  .catch(error => {
    console.error('Error:', error);
    if (callback) {
      callback(null, error);
    }
  });
}

function startCreate() {
  const dataToSend = [];
  const Title = document.getElementById('TitleInput').value.trim();
  const time = document.getElementById('timeInput').value.trim();
  let index = 1;
  let errorDetected = false;

  while (true) {
    const categoryInput = document.getElementById(`input_${index}_category`);
    const wordInput = document.getElementById(`input_${index}_word`);

    if (!categoryInput || !wordInput) {
      break;
    }

    const categoryValue = categoryInput.value.trim();
    const wordValue = wordInput.value.trim();

    const wordValues = wordValue.replace(/["']/g, "");
    const wordsArray = wordValues.split('\n').filter(word => word.trim() !== ''); 
    dataToSend.push({ category: categoryValue, words: wordsArray });

    index++;
  };

  if (errorDetected) {
    console.error('Error: Empty input detected');
    return;
  }

  createGame(dataToSend, Title, time, (responseID, error) => {
    if (error) {
      console.error('Error:', error);
    } else {
      listID = responseID;
      const modal = document.getElementById('myModal');
      const gameIDSpan = document.getElementById('gameID');
      const gameID = listID;
      gameIDSpan.textContent = location.protocol + "//" + location.host + location.pathname.slice(0, -11) + "game.html?id=" + gameID;
      document.getElementById('gameID').href=location.protocol + "//" + location.host + location.pathname.slice(0, -11) + "game.html?id=" + gameID;
      modal.style.display = 'block';
    }
  });
};
