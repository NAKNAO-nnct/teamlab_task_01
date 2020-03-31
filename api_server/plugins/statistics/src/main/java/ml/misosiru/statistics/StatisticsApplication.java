package ml.misosiru.statistics;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.scheduling.annotation.EnableScheduling;
import org.springframework.scheduling.annotation.Scheduled;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;
import org.springframework.web.client.RestTemplate;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

@SpringBootApplication
@RestController
@EnableScheduling
public class StatisticsApplication {
	// URL
	private String URL = "https://task01.trompot.ml/";

	//	データ保存
	private List<Integer> ids = new ArrayList<Integer>();
	private List<List<Integer>> response = new ArrayList<List<Integer>>();

	// スケジューラ
	@Scheduled(fixedRate = 86400000)
	public void check() {
		RestTemplate template = new RestTemplate();
		Products products = template.getForObject(this.URL + "/api/products", Products.class);
		System.out.println(products.details);
		String[] st = String.valueOf(products.details).replaceAll(" ", "").split("\\[\\{")[1].split("]")[0].split("},?\\{?");
		List<Integer> new_ids = convertListStringtoInt(st);
//		int_list_comparison(new_ids, new ArrayList<Integer>(Arrays.asList(1,2,4,5,6,8,9,10,11)));
		this.response = int_list_comparison(new_ids, this.ids);
		this.ids = new_ids;
	}

	// Request
	@RequestMapping("/")
	List<List<Integer>> index(){
		return this.response;
	}

	// IDのリストを返す
	public List<Integer> convertListStringtoInt(String[] data) {
		List<Integer> new_ids = new ArrayList<Integer>();
		for (int i = 0; i < data.length; i++) {
			String id_str = data[i].split(",")[0].split("=")[1].replace("\'", "");
			new_ids.add(Integer.parseInt(id_str));
		}
		return new_ids;
	}

	// listの比較
	public List<List<Integer>> int_list_comparison(List<Integer> a, List<Integer> b){
		List<Integer> add = new ArrayList<Integer>();
		List<Integer> rm = new ArrayList<Integer>();
		for (int c: a){
			if (b.indexOf(c) == -1) {
				rm.add(c);
			}
		}

		for (int c: b){
			if (a.indexOf(c) == -1) {
				add.add(c);
			}
		}
		List<List<Integer>> return_list = new ArrayList<List<Integer>>();
		return_list.add(add);
		return_list.add(rm);
		return return_list;
	}

	public static void main(String[] args) {
		SpringApplication.run(StatisticsApplication.class, args);
	}

}
