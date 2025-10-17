// Wait for the DOM to be ready before executing the script.
document.addEventListener("DOMContentLoaded", function () {
  const appWrapper = document.getElementById("team-app-wrapper");

  // If the main container doesn't exist on the page, stop the script.
  if (!appWrapper) {
    return;
  }

  const buttonContainer = appWrapper.querySelector(".team-filter-buttons");
  const membersContainer = appWrapper.querySelector(".team-members-wrapper");
  const loadingMessage = appWrapper.querySelector(".team-loading-message");

  // The main function to initialize the application.
  async function initTeamApp() {
    try {
      // Fetch the team member data from the custom REST API endpoint.
      // sdm_data.rest_url is passed from PHP via wp_localize_script.
      const response = await fetch(sdm_data.rest_url);
      if (!response.ok) {
        throw new Error("Network response was not ok.");
      }
      const members = await response.json();

      // Once data is fetched, hide the loading message.
      loadingMessage.style.display = "none";

      // Populate the app with the fetched data.
      renderButtons(members);
      renderMembers(members);
      setupFiltering();
    } catch (error) {
      // Display an error message if the fetch fails.
      membersContainer.innerHTML =
        "<p>Failed to load team members. Please try again later.</p>";
      console.error("Error fetching team members:", error);
    }
  }

  // Renders the filter buttons dynamically based on available categories.
  function renderButtons(members) {
    // Use a Set to get a unique list of categories.
    const categories = new Set(members.map((member) => member.category));

    // Generate the HTML for the buttons.
    let buttonsHtml =
      '<button class="filter-button active" data-filter="all">All</button>';
    categories.forEach((category) => {
      if (category) {
        // Ensure the category is not empty
        const capitalizedCategory =
          category.charAt(0).toUpperCase() + category.slice(1);
        buttonsHtml += `<button class="filter-button" data-filter="${category}">${capitalizedCategory}</button>`;
      }
    });

    buttonContainer.innerHTML = buttonsHtml;
  }

  // Renders the team member cards into the container.
  function renderMembers(members) {
    let membersHtml = "";
    members.forEach((member) => {
      membersHtml += `
                <div class="team-member" data-category="${member.category}">
                    <img src="${member.photo}" alt="${member.name}">
                    <h3>${member.name}</h3>
                    <div>${member.bio}</div>
                </div>
            `;
    });

    membersContainer.innerHTML = membersHtml;
  }

  // Sets up the click event listeners for the filtering logic.
  function setupFiltering() {
    buttonContainer.addEventListener("click", function (e) {
      // Use event delegation to handle clicks on buttons.
      if (!e.target.matches(".filter-button")) {
        return;
      }

      const clickedButton = e.target;

      // Update the active state on buttons.
      buttonContainer.querySelector(".active").classList.remove("active");
      clickedButton.classList.add("active");

      const filter = clickedButton.dataset.filter;
      const allMembers = membersContainer.querySelectorAll(".team-member");

      // Apply the filter to each member card.
      allMembers.forEach((member) => {
        const category = member.dataset.category;
        if (filter === "all" || category === filter) {
          member.classList.remove("hidden");
        } else {
          member.classList.add("hidden");
        }
      });
    });
  }

  // Start the application.
  initTeamApp();
});
