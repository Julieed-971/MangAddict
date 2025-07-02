// import nautiljon scraper module
const nautiljonScraper = require('nautiljon-scraper');
// import filesystem module
const fs = require('fs');
// import path module
const path = require('path');


// Path to the mangadata.json file
const dataFilePath = path.join(__dirname, '../', 'mangadata.json');

// scrapeManga function to get URL using search and mangaInfo with getInfoFromUrl
async function scrapeManga() {
	try {
		// Use scraper search function to get results based on a manga title
		const searchResults = await nautiljonScraper.search('gun', 'manga', 20, {typesExclude: ['Hentai']});

		// Read existing data from mangadata.json
		let existingData = [];
		if (fs.existsSync(dataFilePath)) {
			const rawData = fs.readFileSync(dataFilePath);
			existingData = JSON.parse(rawData);
		}
		// Declare an empty array to collect manga search info results
		let addedMangaDataCount = 0;

		// Loop in the search results
		for (const result of searchResults) {
			
			// Collect info using scraper getInfoFromUrl on result url key
			const mangaInfo = await nautiljonScraper.getInfoFromURL(result.url);
			console.log(result.url);

			// Check if manga name already exists in existingData
            const mangaExists = existingData.some(manga => manga.name === mangaInfo.name);

			if (mangaInfo['name'] !== null && !mangaExists) {
				// Store the info in the mangaData array
				existingData.push(mangaInfo);
				addedMangaDataCount++;
			} else {
				continue;
			}
		}
		fs.writeFileSync(dataFilePath, JSON.stringify(existingData, null, 2));
		console.log('Successfully added %d new manga', addedMangaDataCount);
	} catch (error) {
		console.error('Error during scraping process', error);
	}
}

scrapeManga();