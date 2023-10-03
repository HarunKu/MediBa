package test;
import java.awt.Desktop.Action;
import java.time.Duration;

import org.junit.Ignore;
import org.junit.jupiter.api.AfterAll;
import org.junit.jupiter.api.AfterEach;
import org.junit.jupiter.api.BeforeAll;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Order;
import org.junit.jupiter.api.Test;
import org.openqa.selenium.By;
import org.openqa.selenium.JavascriptExecutor;
import org.openqa.selenium.Keys;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.chrome.ChromeDriver;
import org.openqa.selenium.interactions.Actions;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.Select;
import org.openqa.selenium.support.ui.WebDriverWait;

class sdp {
	private static WebDriver webDriver;
	private static String baseUrl;

	@BeforeAll
	static void setUpBeforeClass() throws Exception {
	}

	@AfterAll
	static void tearDownAfterClass() throws Exception {
		webDriver.quit();
	}

	@BeforeEach
	void setUp() throws Exception {
		System.setProperty("webdriver.chrome.driver","C:\\Users\\harun\\Downloads\\chromedriver_23\\chromedriver.exe");
		webDriver= new ChromeDriver();
		baseUrl="https://mediba-x593i.ondigitalocean.app/";
	}

	@AfterEach
	void tearDown() throws Exception {
	}

	/*  Testing login and logout
	 *    */
	@Test
	@Order(1)
	void loginTest() throws InterruptedException {
		webDriver.get(baseUrl);
		webDriver.manage().window().maximize();
		Thread.sleep(5000);
		
		WebElement email = webDriver.findElement(By.xpath("/html/body/main/section/div/div/div/div[2]/div/div/form/div[1]/input"));
		email.clear();
		email.sendKeys("harun.kunovac@stu.ibu.edu.ba");
		Thread.sleep(2000);
		
		WebElement password = webDriver.findElement(By.xpath("/html/body/main/section/div/div/div/div[2]/div/div/form/div[2]/input"));
		password.clear();
		password.sendKeys("1111");
		Thread.sleep(2000);
		
		WebElement prijaviSebutton = webDriver.findElement(By.xpath("/html/body/main/section/div/div/div/div[2]/div/div/form/button"));
		prijaviSebutton.click();
		Thread.sleep(3000);
		
		WebElement odjaviSebutton = webDriver.findElement(By.xpath("/html/body/header/nav/div/div/div/button"));
		odjaviSebutton.click();
		Thread.sleep(2000);
		
				
}
	
	
	/*  Testing the option to see doctors based on specialty, adding a comment to a doctor, and deleting the comment we added 
	 *    */
	@Test
	@Order(2)
	void reviewTest() throws InterruptedException {
		webDriver.get(baseUrl);
		webDriver.manage().window().maximize();
		Thread.sleep(5000);
		
		WebElement email = webDriver.findElement(By.xpath("/html/body/main/section/div/div/div/div[2]/div/div/form/div[1]/input"));
		email.clear();
		email.sendKeys("harun.kunovac@stu.ibu.edu.ba");
		Thread.sleep(2000);
		
		WebElement password = webDriver.findElement(By.xpath("/html/body/main/section/div/div/div/div[2]/div/div/form/div[2]/input"));
		password.clear();
		password.sendKeys("1111");
		Thread.sleep(2000);
		
		WebElement prijaviSebutton = webDriver.findElement(By.xpath("/html/body/main/section/div/div/div/div[2]/div/div/form/button"));
		prijaviSebutton.click();
		Thread.sleep(2000);
		
		WebElement specialties = webDriver.findElement(By.xpath("/html/body/header/nav/div/div/ul/li[2]/a"));
		specialties.click();
		Thread.sleep(2000);
				
	    WebElement dentist = webDriver.findElement(By.xpath("/html/body/main/div[2]/div[2]/div[2]/div/div/div/button[3]"));
		dentist.click();
		Thread.sleep(2000);
		
		WebElement scroll = webDriver.findElement(By.xpath("/html/body/main/div[2]/div[2]/div[1]/div/div/div[1]/button[1]"));
		scroll.sendKeys(Keys.PAGE_DOWN);
		Thread.sleep(2000);
		
		WebElement sviReviews = webDriver.findElement(By.xpath("/html/body/main/div[2]/div[2]/div[1]/div/div/div[1]/button[1]"));
		sviReviews.click();
		Thread.sleep(2000);
		
		WebElement ocjena = webDriver.findElement(By.xpath("/html/body/div[3]/div/div/div[3]/form/div/div[1]/select"));
		ocjena.click();
		Thread.sleep(2000);
		
		WebElement ocjena5 = webDriver.findElement(By.xpath("/html/body/div[3]/div/div/div[3]/form/div/div[1]/select/option[2]"));
		ocjena5.click();
		Thread.sleep(1000);
		
		WebElement komentar = webDriver.findElement(By.xpath("/html/body/div[3]/div/div/div[3]/form/div/div[2]/input"));
		komentar.sendKeys("Great");
		Thread.sleep(2000);
		
		WebElement dodajButton = webDriver.findElement(By.xpath("/html/body/div[3]/div/div/div[3]/form/div/div[3]/button"));
		dodajButton.click();
		Thread.sleep(3000);
		
		WebElement obrisiButton = webDriver.findElement(By.xpath("/html/body/div[3]/div/div/div[2]/div/div[3]/button"));
		obrisiButton.click();
		Thread.sleep(2000);
		
		
		
				
}
	/*  Adding, editing and deleting specialty as admin
	 *    */
	@Test
	@Order(3)
	void adminTest() throws InterruptedException {
		webDriver.get(baseUrl);
		webDriver.manage().window().maximize();
		Thread.sleep(5000);
		
		WebElement email = webDriver.findElement(By.xpath("/html/body/main/section/div/div/div/div[2]/div/div/form/div[1]/input"));
		email.clear();
		email.sendKeys("harun.kunovac@stu.ibu.edu.ba");
		Thread.sleep(2000);
		
		WebElement password = webDriver.findElement(By.xpath("/html/body/main/section/div/div/div/div[2]/div/div/form/div[2]/input"));
		password.clear();
		password.sendKeys("1111");
		Thread.sleep(2000);
		
		WebElement prijaviSebutton = webDriver.findElement(By.xpath("/html/body/main/section/div/div/div/div[2]/div/div/form/button"));
		prijaviSebutton.click();
		Thread.sleep(2000);
		
		WebElement dodajPosao = webDriver.findElement(By.xpath("/html/body/main/div[2]/button"));
		dodajPosao.click();
		Thread.sleep(3000);
		
		WebElement imePosla = webDriver.findElement(By.xpath("/html/body/div[2]/div/div/form/div[2]/div[1]/input"));
		imePosla.sendKeys("Test");
		Thread.sleep(2000);
		
		WebElement opisPosla = webDriver.findElement(By.xpath("/html/body/div[2]/div/div/form/div[2]/div[2]/input"));
		opisPosla.sendKeys("Test");
		Thread.sleep(2000);
		
		WebElement potvrdi = webDriver.findElement(By.xpath("/html/body/div[2]/div/div/form/div[3]/button[2]"));
		potvrdi.click();
		Thread.sleep(3000);
		
		WebElement scroll = webDriver.findElement(By.xpath("/html/body/main/div[2]/div[2]/div[5]/div/div/div/button[1]"));
		scroll.sendKeys(Keys.PAGE_DOWN);
		Thread.sleep(2000);
		
		WebElement urediPosao = webDriver.findElement(By.xpath("/html/body/main/div[2]/div[2]/div[5]/div/div/div/button[1]"));
		urediPosao.click();
		Thread.sleep(3000);
		
		WebElement imePosla1 = webDriver.findElement(By.xpath("/html/body/div[1]/div/div/div[2]/input[2]"));
		imePosla1.clear();
		imePosla1.sendKeys("Test update");
		Thread.sleep(2000);
		
		WebElement opisPosla1 = webDriver.findElement(By.xpath("/html/body/div[1]/div/div/div[2]/input[3]"));
		opisPosla1.clear();
		opisPosla1.sendKeys("Testni update");
		Thread.sleep(2000);
		
		WebElement sacuvajPromjene = webDriver.findElement(By.xpath("/html/body/div[1]/div/div/div[3]/button[2]"));
		sacuvajPromjene.click();
		Thread.sleep(3000);
		
		WebElement scroll2 = webDriver.findElement(By.xpath("/html/body/main/div[2]/div[2]/div[5]/div/div/div/button[2]"));
		scroll2.sendKeys(Keys.PAGE_DOWN);
		Thread.sleep(2000);
		
		WebElement obrisiPosao = webDriver.findElement(By.xpath("/html/body/main/div[2]/div[2]/div[5]/div/div/div/button[2]"));
		obrisiPosao.click();
		Thread.sleep(3000);
		
		
				
}
	/*  Testing the search on the mediba page 
	 *    */
	@Test
	@Order(4)
	void searchTest() throws InterruptedException {
		webDriver.get(baseUrl);
		webDriver.manage().window().maximize();
		Thread.sleep(5000);
		
		WebElement email = webDriver.findElement(By.xpath("/html/body/main/section/div/div/div/div[2]/div/div/form/div[1]/input"));
		email.clear();
		email.sendKeys("harun.kunovac@stu.ibu.edu.ba");
		Thread.sleep(2000);
		
		WebElement password = webDriver.findElement(By.xpath("/html/body/main/section/div/div/div/div[2]/div/div/form/div[2]/input"));
		password.clear();
		password.sendKeys("1111");
		Thread.sleep(2000);
		
		WebElement prijaviSebutton = webDriver.findElement(By.xpath("/html/body/main/section/div/div/div/div[2]/div/div/form/button"));
		prijaviSebutton.click();
		Thread.sleep(2000);
		
		WebElement doctorsButton = webDriver.findElement(By.xpath("/html/body/header/nav/div/div/ul/li[3]/a"));
		doctorsButton.click();
		Thread.sleep(3000);
		
		WebElement upisUpretragu = webDriver.findElement(By.xpath("/html/body/main/div[1]/div/div/div/form/input"));
		upisUpretragu.sendKeys("sarajevo");
		Thread.sleep(2000);
		
		WebElement pretraziButton = webDriver.findElement(By.xpath("/html/body/main/div[1]/div/div/div/form/button"));
		pretraziButton.click();
		Thread.sleep(3000);
		
		
				
}
	/*  Adding, updating and deleting doctor
	 *    */
	@Test
	@Order(5)
	void doctorTest() throws InterruptedException {
		webDriver.get(baseUrl);
		webDriver.manage().window().maximize();
		Thread.sleep(5000);
		
		WebElement email = webDriver.findElement(By.xpath("/html/body/main/section/div/div/div/div[2]/div/div/form/div[1]/input"));
		email.clear();
		email.sendKeys("harun.kunovac@stu.ibu.edu.ba");
		Thread.sleep(2000);
		
		WebElement password = webDriver.findElement(By.xpath("/html/body/main/section/div/div/div/div[2]/div/div/form/div[2]/input"));
		password.clear();
		password.sendKeys("1111");
		Thread.sleep(2000);
		
		WebElement prijaviSebutton = webDriver.findElement(By.xpath("/html/body/main/section/div/div/div/div[2]/div/div/form/button"));
		prijaviSebutton.click();
		Thread.sleep(2000);
		
		WebElement doctorsPage = webDriver.findElement(By.xpath("/html/body/header/nav/div/div/ul/li[3]/a"));
		doctorsPage.click();
		Thread.sleep(3000);
		
		WebElement doctorsAdd = webDriver.findElement(By.xpath("/html/body/main/div[2]/button"));
		doctorsAdd.click();
		Thread.sleep(3000);
		
		WebElement specialty = webDriver.findElement(By.xpath("/html/body/div[1]/div/div/form/div[2]/div[1]/select"));
		specialty.click();
		Thread.sleep(2000);
		
		WebElement specialtyPick = webDriver.findElement(By.xpath("/html/body/div[1]/div/div/form/div[2]/div[1]/select/option[3]"));
		specialtyPick.click();
		Thread.sleep(2000);
		
		WebElement name = webDriver.findElement(By.xpath("/html/body/div[1]/div/div/form/div[2]/div[2]/input"));
		name.sendKeys("Test");
		Thread.sleep(2000);
		
		WebElement emailDoctor = webDriver.findElement(By.xpath("/html/body/div[1]/div/div/form/div[2]/div[3]/input"));
		emailDoctor.sendKeys("test@test.com");
		Thread.sleep(2000);
		
		WebElement cityDoctor = webDriver.findElement(By.xpath("/html/body/div[1]/div/div/form/div[2]/div[4]/input"));
		cityDoctor.sendKeys("Test");
		Thread.sleep(2000);
		
		WebElement phoneDoctor = webDriver.findElement(By.xpath("/html/body/div[1]/div/div/form/div[2]/div[5]/input"));
		phoneDoctor.sendKeys("03040234032");
		Thread.sleep(2000);
		
		WebElement addressDoctor = webDriver.findElement(By.xpath("/html/body/div[1]/div/div/form/div[2]/div[6]/input"));
		addressDoctor.sendKeys("Test");
		Thread.sleep(2000);
		
		WebElement descriptionDoctor = webDriver.findElement(By.xpath("/html/body/div[1]/div/div/form/div[2]/div[7]/textarea"));
		descriptionDoctor.sendKeys("Test");
		Thread.sleep(2000);
				
		WebElement saveDoctor = webDriver.findElement(By.xpath("/html/body/div[1]/div/div/form/div[3]/button[2]"));
		saveDoctor.click();
		Thread.sleep(2000);
		
		WebElement scrollDown = webDriver.findElement(By.xpath("/html/body/main/div[2]/div[2]/div[3]/div/div/div[1]/button[2]"));
		scrollDown.sendKeys(Keys.PAGE_DOWN);
		Thread.sleep(3000);
		
		WebElement scroll = webDriver.findElement(By.xpath("/html/body/main/div[2]/div[2]/div[6]/div/div/div[1]/button[2]"));
		scroll.sendKeys(Keys.PAGE_DOWN);
		Thread.sleep(3000);
		
		WebElement editDoctor = webDriver.findElement(By.xpath("/html/body/main/div[2]/div[2]/div[6]/div/div/div[1]/button[2]"));
		editDoctor.click();
		Thread.sleep(2000);
		
		WebElement ime = webDriver.findElement(By.xpath("/html/body/div[2]/div/div/form/div[2]/div[1]/input"));
		ime.clear();
		ime.sendKeys("Test update");
		Thread.sleep(2000);
		
		WebElement saveChanges = webDriver.findElement(By.xpath("/html/body/div[2]/div/div/form/div[3]/button[2]"));
		saveChanges.click();
		Thread.sleep(2000);
		
		WebElement scrollDown2 = webDriver.findElement(By.xpath("/html/body/main/div[2]/div[2]/div[3]/div/div/div[1]/button[3]"));
		scrollDown2.sendKeys(Keys.PAGE_DOWN);
		Thread.sleep(3000);
		
		WebElement scroll2 = webDriver.findElement(By.xpath("/html/body/main/div[2]/div[2]/div[6]/div/div/div[1]/button[3]"));
		scroll2.sendKeys(Keys.PAGE_DOWN);
		Thread.sleep(3000);
		
		WebElement deleteDoctor = webDriver.findElement(By.xpath("/html/body/main/div[2]/div[2]/div[6]/div/div/div[1]/button[3]"));
		deleteDoctor.click();
		Thread.sleep(3000);
		
			
		
				
}

}