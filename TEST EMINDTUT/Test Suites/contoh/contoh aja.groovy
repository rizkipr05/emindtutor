import static com.kms.katalon.core.checkpoint.CheckpointFactory.findCheckpoint
import static com.kms.katalon.core.testcase.TestCaseFactory.findTestCase
import static com.kms.katalon.core.testdata.TestDataFactory.findTestData
import static com.kms.katalon.core.testobject.ObjectRepository.findTestObject
import static com.kms.katalon.core.testobject.ObjectRepository.findWindowsObject
import com.kms.katalon.core.checkpoint.Checkpoint as Checkpoint
import com.kms.katalon.core.cucumber.keyword.CucumberBuiltinKeywords as CucumberKW
import com.kms.katalon.core.mobile.keyword.MobileBuiltInKeywords as Mobile
import com.kms.katalon.core.model.FailureHandling as FailureHandling
import com.kms.katalon.core.testcase.TestCase as TestCase
import com.kms.katalon.core.testdata.TestData as TestData
import com.kms.katalon.core.testng.keyword.TestNGBuiltinKeywords as TestNGKW
import com.kms.katalon.core.testobject.TestObject as TestObject
import com.kms.katalon.core.webservice.keyword.WSBuiltInKeywords as WS
import com.kms.katalon.core.webui.keyword.WebUiBuiltInKeywords as WebUI
import com.kms.katalon.core.windows.keyword.WindowsBuiltinKeywords as Windows
import internal.GlobalVariable as GlobalVariable
import org.openqa.selenium.Keys as Keys

WebUI.openBrowser('')

WebUI.navigateToUrl('https://emindtutor.com/login')

WebUI.setText(findTestObject('Object Repository/aktifkan syarikat/Page_Login - eMindTutor/input_Email_email'), 'superadmin@example.com')

WebUI.setEncryptedText(findTestObject('Object Repository/aktifkan syarikat/Page_Login - eMindTutor/input_Password_password'), 
    '8SQVv/p9jVTHLrggi8kCzw==')

WebUI.sendKeys(findTestObject('Object Repository/aktifkan syarikat/Page_Login - eMindTutor/input_Password_password'), Keys.chord(
        Keys.ENTER))

WebUI.click(findTestObject('Object Repository/aktifkan syarikat/Page_Dashboard  EMindTutor/a_Companies'))

WebUI.click(findTestObject('Object Repository/aktifkan syarikat/Page_EMindTutor/button_Expired                             _445642'))

WebUI.click(findTestObject('Object Repository/aktifkan syarikat/Page_EMindTutor/svg_Expired                                _f32ba5'))

WebUI.closeBrowser()

