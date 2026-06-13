
        

        document.addEventListener("DOMContentLoaded", function () {
            loadHTML("notification", "notification.php");
            
            
        });
        
        


 
        function loadHTML(id, url) {
            let element = document.getElementById(id);
            if (!element) {
                console.error(`Element with ID "${id}" not found.`);
                return; // Stop execution if the element is missing
            }
        
            fetch(url)
            .then(response => response.text())
            .then(data => element.innerHTML = data)
            .catch(error => console.error(`Error loading ${url}:`, error));
        }
        
        

       





        
