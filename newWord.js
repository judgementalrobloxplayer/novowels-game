async function getWord(callback) {
  try {
      const queryString = window.location.search;
      const urlParams = new URLSearchParams(queryString);
      const gameId = urlParams.get('id');

      if (gameId == null) {
          console.error('Game ID is null');
          if (callback) {
              callback(null, 'Game ID is null');
          }
      } else {
          const newWordData = new FormData();
          newWordData.append('type', 'newWord');
          newWordData.append('listID', gameId);

          const response = await fetch('./fetch.php', {
              method: 'POST',
              body: newWordData
          });

          if (!response.ok) {
              throw new Error('Network response was not ok');
          }

          const data = await response.json();

          if (data.exists == 1) {
              const result = {
                  exists: true,
                  data: data
              };
              
              if (callback) {
                  callback(result, null);
              }
          } else {
              console.error('Data does not exist');

              if (callback) {
                  callback(null, 'Data does not exist');
              }
          }
      }
  } catch (error) {
      console.error('Error:', error);

      if (callback) {
          callback(null, error);
      }
  }
}