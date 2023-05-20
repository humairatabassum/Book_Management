// Fetch the API data
fetch("http://localhost/BookManagementSystem/api.php")
  .then(response => response.json())
  .then(data => {
  
    const table = document.createElement("table");
    const thead = document.createElement("thead");
    const tbody = document.createElement("tbody");

    const headers = ["Title", "Publisher", "Age"];
    const headerRow = document.createElement("tr");
    headers.forEach(headerText => {
      const th = document.createElement("th");
      th.textContent = headerText;
      headerRow.appendChild(th);
    });
    thead.appendChild(headerRow);

    data.forEach(book => {
      const row = document.createElement("tr");
      const titleCell = document.createElement("td");
      titleCell.textContent = book.title;
      const publisherCell = document.createElement("td");
      publisherCell.textContent = book.publisher;
      const ageCell = document.createElement("td");
      ageCell.textContent = book.age;

      row.appendChild(titleCell);
      row.appendChild(publisherCell);
      row.appendChild(ageCell);
      tbody.appendChild(row);
    });

    table.appendChild(thead);
    table.appendChild(tbody);
    document.body.appendChild(table);
  })
  .catch(error => {
    console.error(error);
    
  });
