# WebComponents Design

```mermaid
graph TD
    App --> Info
    App --> Standard
    App --> ResultsView
    App --> Breeder
    App --> Moderator
    App --> Admin
    
    App --> Login

    Info --> Article
    
    Standard --> Sections
    Sections --> Section
    Section --> Breeds
    Breeds --> Breed
    Breed --> Colors
    Colors --> Color
    
    ResultsView --> DonutChart
    ResultsView --> GeoChart
    ResultsView --> TimeLineChart
    ResultsView --> ResultsDoc
    
    Breeder --> BreederDetails
    Breeder --> Reports
    Reports --> Report
    
    Moderator --> Districts
    Districts --> District
    District --> Results
    District --> Breeders
    District --> ResultsDoc
    
    Breeders --> Breeder
    
    Admin --> Districts
    Admin --> Articles
    Admin --> Logs

    Articles --> Article
```