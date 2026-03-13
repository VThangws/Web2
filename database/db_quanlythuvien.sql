-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 12, 2026 at 06:58 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_quanlythuvien`
--

-- --------------------------------------------------------

--
-- Table structure for table `ctphieumuon`
--

CREATE TABLE `ctphieumuon` (
  `mamuon` varchar(50) NOT NULL,
  `macuonsach` varchar(50) NOT NULL,
  `tinhtrang_truoc` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ctphieunhap`
--

CREATE TABLE `ctphieunhap` (
  `maphieunhap` varchar(50) NOT NULL,
  `madausach` varchar(50) NOT NULL,
  `dongianhap` decimal(10,2) DEFAULT NULL,
  `soluong` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ctphieuphat`
--

CREATE TABLE `ctphieuphat` (
  `maphat` varchar(50) NOT NULL,
  `macuonsach` varchar(50) NOT NULL,
  `lydo` varchar(255) DEFAULT NULL,
  `songayquahan` int(11) DEFAULT NULL,
  `sotienphat` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ctphieutra`
--

CREATE TABLE `ctphieutra` (
  `matra` varchar(50) NOT NULL,
  `macuonsach` varchar(50) NOT NULL,
  `maphat` varchar(50) NOT NULL,
  `tinhtrang_sau` varchar(50) DEFAULT NULL,
  `tienphathuha` decimal(10,2) DEFAULT NULL,
  `songayquahan` int(11) DEFAULT NULL,
  `tienphatquahan` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ctquyen`
--

CREATE TABLE `ctquyen` (
  `manhomquyen` varchar(50) NOT NULL,
  `machucnang` varchar(50) NOT NULL,
  `hanhdong` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cuonsach`
--

CREATE TABLE `cuonsach` (
  `macuonsach` varchar(50) NOT NULL,
  `madausach` varchar(50) DEFAULT NULL,
  `mavitri` varchar(50) DEFAULT NULL,
  `trangthai` varchar(50) DEFAULT NULL,
  `tinhtrang` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cuonsach`
--

INSERT INTO `cuonsach` (`macuonsach`, `madausach`, `mavitri`, `trangthai`, `tinhtrang`) VALUES
('CS001', 'DS001', 'VT001', 'SanSang', 'Moi'),
('CS002', 'DS001', 'VT001', 'SanSang', 'Tot'),
('CS003', 'DS001', 'VT001', 'DangMuon', 'Tot'),
('CS004', 'DS002', 'VT002', 'SanSang', 'Moi'),
('CS005', 'DS002', 'VT002', 'DangMuon', 'Moi'),
('CS006', 'DS002', 'VT002', 'Hong', 'HuHong');

-- --------------------------------------------------------

--
-- Table structure for table `danhmucchucnang`
--

CREATE TABLE `danhmucchucnang` (
  `machucnang` varchar(50) NOT NULL,
  `tenchucnang` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dausach`
--

CREATE TABLE `dausach` (
  `madausach` varchar(50) NOT NULL,
  `tensach` varchar(255) DEFAULT NULL,
  `namxuatban` int(11) DEFAULT NULL,
  `dongia` decimal(10,2) DEFAULT NULL,
  `matacgia` varchar(50) DEFAULT NULL,
  `matheloai` varchar(50) DEFAULT NULL,
  `manxb` varchar(50) DEFAULT NULL,
  `mota` text DEFAULT NULL,
  `anhbia` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dausach`
--

INSERT INTO `dausach` (`madausach`, `tensach`, `namxuatban`, `dongia`, `matacgia`, `matheloai`, `manxb`, `mota`, `anhbia`) VALUES
('DS001', 'Cách Tạo Video Triệu View', 2026, 268000.00, 'TG001', 'TL001', 'NXB003', 'Cách Tạo Video Triệu View\n\nBAN SẼ HOC ĐƯỢC GÌ TỪ CUỐN SÁCH NÀY?\n\n- Kiến thức nhập môn liên quan đến kịch bản video ngắn bao gồm cấu trúc tinh tiết và phân tích tâm lý, thông qua các bước cư thể như lựa chọn chủ để, lên kế hoạch chủ để, quay phim, cách biểu đạt tới thoại và ngôn ngữ mấy quay.\n\n- 129 kỳ thuật viết kịch bản bao cảm xác định nội dung chính, tình tiết cao tiếng vang thiết lập các nút thắc, quản lý tiết tấu cầu chuyện, xây dựng nhân vật, bố cục cảnh quay.\n\n- Các nguyên tốc viết kịch bản, logic cảm xúc và kỳ thuật tạo xung đột, đẩy cảm xúc đến cao trào.\n\nĐể tạo được một video triệu view, có khả năng viral trên mạng xã hội, đói hỏi các nhà sáng tạo nội dung phải có một kịch bán hấp dẫn, lôi cuốn Và thông qua các góc nhìn đa chiêu, cuốn sách này giống như một cuốn cẩm nang toàn diện giúp bạn trở thành một biên kịch video ngắn xuất sắc.\n\nVới bố cục rõ ràng cuốn sách phủ hợp với những người mới bước chân vào ngành biên kịch video ngắn, cùng như các nhớ sáng tạo biên cập viên video ngắn, phim truyện hình dồi tập, chương trình truyền hình và phim ảnh Sách cùng có thể dùng làm giáo trình tham khảo cho sinh viên đại học chuyên ngành điện ảnh, truyên hình và kịch.', '8935235247314_4fb356aaf56f479f929b31c15c378e2a_medium.jpg'),
('DS002', 'Đặt Tên Thương Hiệu', 2024, 70000.00, 'TG002', 'TL001', 'NXB002', 'Đặt Tên Thương Hiệu\n\nTên thương hiệu là một thành tố cực kỳ quan trọng của thương hiệu, vì nó chính là sự khác biệt đầu tiên và lớn nhất giữa sản phẩm/ dịch vụ này với sản phẩm/ dịch vụ khác cùng loại.\n\nTrong dài hạn, thương hiệu cũng chỉ tồn tại dưới dạng một cái tên. Và theo thời gian, nó cũng chính là tài sản lớn nhất của doanh nghiệp.\n\nCái tên có thể làm nên một thương hiệu mạnh. Vì vậy sở hữu một tên độc đáo và hấp dẫn sẽ là một lợi thế rất lớn. Nó không chỉ giúp bạn khác biệt với đối thủ cạnh tranh mà còn gây thiện cảm và kết nối với khách hàng mục tiêu một cách đầy cảm xúc.\n\nChính vì vậy đặt tên thương hiệu bao giờ cũng là việc trọng tâm của bất kì một kế hoạch phát triển thương hiệu nào.\n\nĐây là cuốn đầu tiên của tác giả trong combo 3 cuốn viết về thương hiệu.\n\nCuốn thứ hai sẽ viết các bước còn lại của quy trình xây dựng thương hiệu doanh nghiệp gồm: định vị thương hiệu, tính cách thương hiệu, kiến trúc thương hiệu và truyền thông thương hiệu.\n\nCuốn này có tựa là \"Định vị thương hiệu\".\n\nVà cuốn thứ ba sẽ viết về thương hiệu cá nhân, bao gồm cách thức và các bước cụ thể để xây dựng một thương hiệu cá nhân nổi bật.', 'b_a---_t-t_n-th_ng-hi_u_fc80c58702074f9c8023365cee90350e_medium.jpg'),
('DS003', 'Ứng Dụng Agile Marketing', 2025, 195000.00, 'TG004', 'TL001', 'NXB004', 'Ứng Dụng Agile Marketing\n\nTrong một thị trường thay đổi từng ngày, marketing không thể vận hành theo lối mòn. Các doanh nghiệp và nhà quản lý cần một phương pháp tư duy linh hoạt, có khả năng thích ứng nhanh với biến động nhưng vẫn đảm bảo hiệu quả bền vững. Và đó chính là giá trị cốt lõi mà “Ứng Dụng Agile Marketing” mang đến.\n\nDựa trên nguyên lý Agile tư duy đã tạo nên bước ngoặt trong lĩnh vực phát triển phần mềm. Cuốn sách chỉ ra cách đưa sự linh hoạt, thử nghiệm và tối ưu liên tục vào chiến lược marketing. Không dừng lại ở lý thuyết, sách đưa ra khung làm việc rõ ràng, case study thực tiễn và công cụ triển khai cụ thể, giúp đội ngũ marketing:\n\n- Rút ngắn thời gian từ ý tưởng đến hành động.\n\n- Đo lường và cải tiến chiến dịch liên tục.\n\n- Thích ứng nhanh với hành vi và nhu cầu khách hàng luôn biến đổi.\n\n- Tăng tính phối hợp đa chức năng trong doanh nghiệp, phá vỡ “silo” giữa các bộ phận.\n\nĐiểm đặc biệt của cuốn sách là cách tiếp cận “thực chiến”: mỗi chương đều gắn với bài học thực tế, từ quản lý backlog, sprint, đến việc áp dụng Kanban và Scrum trong marketing. Thay vì những khái niệm mơ hồ, tất cả đều được minh họa bằng quy trình và tình huống cụ thể để người đọc có thể áp dụng ngay.\n\n“Ứng Dụng Agile Marketing” là tài liệu không thể thiếu cho:\n\n- Nhà quản lý và lãnh đạo doanh nghiệp muốn xây dựng đội ngũ marketing linh hoạt và hiệu quả.\n\n- Marketer, chuyên viên truyền thông, digital muốn nâng cao năng lực thích ứng và sáng tạo.\n\n- Doanh nhân khởi nghiệp cần cách tiếp cận marketing tinh gọn nhưng vẫn hiệu quả.\n\n- Sinh viên, học viên MBA và nhà nghiên cứu quan tâm đến xu hướng marketing hiện đại.', 'ung-dung-agile-marketing---converted-01_576b070204534ec38be121b19d116aa8_medium.jpg'),
('DS004', 'Manifest Marketing', 2025, 159000.00, 'TG003', 'TL001', 'NXB004', 'Manifest Marketing\n\nTrong một thế giới kỹ thuật số ngày càng minh bạch, nơi thông tin về sản phẩm có thể được truy cập tức thì chỉ bằng một cú nhấp chuột, niềm tin đã trở thành yếu tố sống còn của mọi chiến lược marketing. Những phương pháp tiếp thị truyền thống, nặng về thao túng và thiếu chân thực, đang dần mất hiệu lực trong một thị trường mà người tiêu dùng biết nhiều như người bán. Trước bối cảnh đó, \"Manifest Marketing\" của Joe Vitale đưa ra một cách tiếp cận hoàn toàn khác: tiếp thị nhân quả - một mô hình lấy \"cho đi vô điều kiện\" làm nền tảng để xây dựng lòng tin và tạo ảnh hưởng sâu sắc.\n\nCuốn sách không đơn thuần là lý thuyết, mà là một bản hướng dẫn thực tiễn được hình thành từ chính trải nghiệm sống và kinh doanh của tác giả, một chuyên gia marketing từng đi lên từ con số không. Với giọng văn mạch lạc, dễ tiếp cận nhưng không thiếu chiều sâu, Joe Vitale lần lượt khai mở:\n\n- Cốt lõi của marketing nhân quả: cho đi không điều kiện, để nhận lại niềm tin và sự gắn bó từ khách hàng.\n\n- Những rào cản tâm lý khiến con người sợ phải \"cho\", và cách vượt qua để kiến tạo dòng chảy thịnh vượng.\n\n- Cách áp dụng nguyên lý nhân quả vào tiếp thị qua internet, truyền thông xã hội, email và quan hệ công chúng.\n\n- Những nguyên tắc bất biến trong tiếp thị, dù công cụ có đổi thay.\n\nĐiều khiến “Manifest Marketing” khác biệt chính là tính kết nối giữa tâm lý học, hành vi tiêu dùng và triết lý sống. Marketing ở đây không còn là kỹ năng thao túng mà trở thành nghệ thuật lan tỏa giá trị thực. Người đọc sẽ nhận ra rằng: tiếp thị không chỉ để bán hàng, mà còn là một hành trình kiến tạo ảnh hưởng tích cực đến cộng đồng, và chính cuộc đời mình.\n\nDành cho các nhà tiếp thị, doanh nhân, chuyên gia thương hiệu hoặc bất kỳ ai muốn trở thành người truyền cảm hứng bằng sự chân thành và giá trị thực, \"Manifest Marketing\" không chỉ là một cuốn sách về kỹ thuật tiếp thị, mà là lời mời gọi tái định nghĩa lại cách tạo ra ảnh hưởng trong thời đại mới.', 'manifest-marketing---b_a-1_4357b56059e34434b9e1b8c99d30cedc_medium.jpg'),
('DS005', 'Công Thức Bán Hàng Bất Bại', 2025, 88000.00, 'TG005', 'TL001', 'NXB002', 'Công Thức Bán Hàng Bất Bại  Bạn là người bán hàng, chủ doanh nghiệp hay đang khởi nghiệp? Bạn muốn tăng doanh số, chốt đơn nhanh chóng và xây dựng mối quan hệ bền vững với khách hàng? Cuốn sáchCông thức bán hàng bất bại: Làm chủ tâm lý - Thống trị doanh sốcủa tác giả Khôi chính là chìa khóa bạn cần!  Vì sao bạn nên đọc cuốn sách này?  Hiểu tâm lý khách hàng: Nắm bắt suy nghĩ, cảm xúc và hành vi của khách hàng để đưa ra giải pháp phù hợp.  Xây dựng chiến lược bán hàng hiệu quả: Áp dụng các kỹ thuật và chiến thuật đã được kiểm chứng để tăng tỷ lệ chốt đơn.  Phát triển kỹ năng giao tiếp: Học cách lắng nghe, thuyết phục và tạo dựng niềm tin với khách hàng.  Đối mặt với từ chối: Biến mỗi lần từ chối thành cơ hội học hỏi và cải thiện.  Nội dung cuốn sách bao gồm:  Phân tích tâm lý khách hàng trong từng giai đoạn mua hàng.  Các bước xây dựng quy trình bán hàng từ tiếp cận đến chốt đơn.  Kỹ thuật xử lý từ chối và biến khách hàng tiềm năng thành khách hàng trung thành.  Cách tạo dựng thương hiệu cá nhân và uy tín trong mắt khách hàng.  Lợi ích khi áp dụng công thức bán hàng bất bại:  Tăng doanh số bán hàng một cách bền vững.  Xây dựng mối quan hệ lâu dài với khách hàng.  Phát triển kỹ năng bán hàng chuyên nghiệp và tự tin.  Đối tượng phù hợp:  Người mới bắt đầu trong lĩnh vực bán hàng.  Chủ doanh nghiệp muốn cải thiện doanh số.  Những ai muốn nâng cao kỹ năng giao tiếp và thuyết phục.', '9786326042788_80ee7caa4bfa488fa274d1b90a563f91_medium.jpg'),
('DS006', 'Đổi Mới Với Intergrated Brand Thinking', 2025, 250000.00, 'TG006', 'TL001', 'NXB005', '“Đổi Mới Với Integrated Brand Thinking” là cuốn sách thứ hai trong bộ ba ấn phẩm về xây dựng thương hiệu của tác giả Richard Moore, đem đến hành trình khám phá sâu rộng các mô hình thương hiệu từ những năm 50 đến nay, cũng như giới thiệu phương pháp “Integrated Brand Thinking” để giúp doanh nghiệp xây dựng thương hiệu toàn diện và hiệu quả hơn trong thời kỳ hậu COVID.\n\nChủ đề trọng tâm - Tư duy Thương hiệu Tích hợp (Integrated Brand Thinking - IBT) là một bước tiến vượt bậc so với phương pháp Nhận diện Thương hiệu truyền thống. Xuất phát từ thấu hiểu bản chất thương hiệu, IBT sẽ khiến hình ảnh thương hiệu trở nên sống động hơn, vừa tối ưu hóa việc áp dụng tích cách thương hiệu trong nội bộ, vừa củng cố hình ảnh khác biệt ra bên ngoài, thấu hiểu và đồng hành cùng khách hàng như những người bạn đích thực.\n\nCuốn sách không chỉ mang đến góc nhìn mới mẻ về bản chất của thương hiệu mà còn là kho tàng kiến thức phong phú về các mô hình thương hiệu nổi tiếng trong suốt 70 năm qua. Đặc biệt, tác giả Richard Moore đã trình bày một cách đầy đủ và chi tiết các nguyên tắc cốt lõi của IBT và minh họa bằng nhiều ví dụ thực tiễn, sáng tạo.\n\nVề Richard Moore, ông là Nhà sáng lập, Chủ tịch kiêm Tổng Giám đốc Sáng tạo của Công ty Tư vấn Hình ảnh Thương hiệu Richard Moore Associates, đơn vị đồng hành cùng hơn 100 doanh nghiệp hàng đầu tại Việt Nam trong việc xây dựng và phát triển bản sắc nhận diện thương hiệu toàn diện.\n\nNếu bạn muốn kiến tạo một hình ảnh thương hiệu thống nhất, sâu sắc, có tính linh hoạt cao và dễ dàng điều chỉnh để phù hợp với mọi quy mô và đặc thù doanh nghiệp, thì chắc chắn “Đổi Mới Với Integrated Brand Thinking” sẽ là cuốn sách bạn nhất định phải đọc. Đừng bỏ lỡ!', '8936170870902_e6e16aea8b3144edbe8fc40862c97744_medium.jpg'),
('DS007', 'Thuật Cạnh Tranh', 2025, 229000.00, 'TG007', 'TL001', 'NXB004', 'Thuật Cạnh Tranh\n\nTại sao cùng một món ăn nhưng nó lại trở nên ngon miệng hơn chỉ nhờ cách trình bày bắt mắt? Vì sao một chai rượu được cho là “đắt tiền” lại khiến ta cảm nhận vị ngon sâu sắc hơn, dù thực chất chỉ là sản phẩm bình dân? “Thuật Cạnh Tranh” là một lời giải cho những câu hỏi này, và còn nhiều hơn thế nữa.\n\nCuốn sách là sự kết hợp giữa khoa học thần kinh, tâm lý học nhận thức và kinh nghiệm thực chiến trong lĩnh vực marketing, nhằm khám phá cách các thương hiệu hiện đại len lỏi vào hệ thần kinh của người tiêu dùng để tác động lên cảm xúc, hành vi và quyết định mua sắm của họ.\n\nKhông đơn thuần là một cuốn sách về kỹ thuật tiếp thị, “Thuật Cạnh Tranh” giúp người đọc thấu hiểu bản chất của những thao tác tinh vi đang diễn ra đằng sau từng cú click chuột, từng mẫu quảng cáo tưởng như vô hại. Với những nghiên cứu khoa học và ví dụ cụ thể, cuốn sách lý giải tại sao một chiến dịch truyền thông lại khiến người tiêu dùng “nghiện” sản phẩm mà không hề hay biết, và làm thế nào để thương hiệu tạo ra sự gắn bó cảm xúc lâu dài.\n\n12 chương sách là 12 lớp lang khám phá từ hiệu ứng thị giác mù, sức mạnh của cảm xúc, mùi vị và ký ức, đến cách các thương hiệu “đọc vị” bản đồ tư duy và thay đổi hành vi tiêu dùng. Đây là hành trình từ nhận thức đến vô thức, từ lý trí đến cảm xúc, nơi những quyết định tưởng chừng cá nhân lại được dẫn dắt bởi hệ thống được thiết kế kỹ lưỡng bởi các nhà tiếp thị.\n\n“Thuật Cạnh Tranh” không chỉ hữu ích với những người làm marketing, bán hàng, mà còn là cuốn cẩm nang giúp người tiêu dùng hiểu rõ bản than, để trở thành người cầm lái thay vì chỉ là hành khách trong hành trình tiêu dùng đầy cám dỗ.', 'thu_t-c_nh-tranh---b_a-1_53f298b3db0d4b6a9d70df8e2ef1e76f_medium.jpg'),
('DS008', 'Chiến Lược Marketing Thực Chiến', 2025, 169000.00, 'TG008', 'TL001', 'NXB004', 'Chiến Lược Marketing Thực Chiến\n\nTrong một thế giới mà hành vi tiêu dùng không ngừng biến chuyển bởi công nghệ và dữ liệu, năng lực xây dựng chiến lược marketing vừa linh hoạt vừa hiệu quả đang trở thành yếu tố sống còn đối với các doanh nghiệp. Chiến Lược Marketing Thực Chiến của Dan White là một cẩm nang được thiết kế để đáp ứng chính nhu cầu đó – giúp người đọc không chỉ hiểu mà còn có thể áp dụng các nguyên lý marketing vào thực tiễn một cách trực tiếp và có hệ thống.\n\nĐiểm nổi bật của cuốn sách nằm ở cấu trúc rõ ràng, trực quan và tính ứng dụng cao. Dan White chia toàn bộ hành trình marketing thành 10 phần từ phát triển thương hiệu, trải nghiệm khách hàng, đổi mới, truyền thông, đến đo lường và mở rộng thương hiệu. Mỗi phần trình bày những khái niệm nền tảng đi kèm với hình ảnh minh họa sinh động và các khung tư duy đã được kiểm chứng. Đây là điểm đặc biệt giúp ngay cả những người mới bước chân vào lĩnh vực marketing cũng có thể nắm bắt và vận dụng một cách hiệu quả.\n\nKhông sa đà vào lý thuyết trừu tượng hay thuật ngữ chuyên ngành phức tạp, Chiến Lược Marketing Thực Chiến tập trung vào giải pháp: cách xác định đúng mục tiêu, lập kế hoạch cụ thể, thấu hiểu tâm lý khách hàng, tối ưu chi phí quảng cáo và sáng tạo truyền thông độc đáo. Những nội dung tưởng chừng rời rạc như xây dựng thương hiệu hay đo lường hiệu quả đều được kết nối khéo léo để phục vụ một mục tiêu chung: thúc đẩy doanh số và phát triển bền vững.\n\nCuốn sách không chỉ dành cho marketer chuyên nghiệp mà còn phù hợp với nhà khởi nghiệp, chủ doanh nghiệp nhỏ và bất kỳ ai muốn học hỏi bài bản về marketing hiện đại. Đây là bản hướng dẫn thiết yếu cho hành trình tạo dựng giá trị thương hiệu và khả năng cạnh tranh thực chất trong bối cảnh kinh doanh đầy biến động.', 'b_a-1_5_34_216867ec60e340409dcde4350f989732_medium.jpg'),
('DS009', 'Human Branding - Nuôi Dưỡng Thương Hiệu Bằ̀ng Sự Thấu Cảm', 2025, 219000.00, 'TG009', 'TL001', 'NXB004', 'Human Branding - Nuôi Dưỡng Thương Hiệu Bằ̀ng Sự Thấu Cảm\n\nChúng ta kết nối với thương hiệu giống như cách chúng ta kết nối với con người – bằng cảm xúc, niềm tin và những tương tác có ý nghĩa. Từ việc lựa chọn sản phẩm trên website đến việc trực tiếp mua sắm tại cửa hàng, người tiêu dùng luôn tìm kiếm một sự kết nối. Những thương hiệu thành công nhất không chỉ cung cấp sản phẩm hay dịch vụ, mà còn biết cách tạo ra sự gắn bó về cảm xúc, gây dựng tình yêu thương hiệu từ những trải nghiệm để đời.\n\nTương tự như cách chúng ta duy trì các mối quan hệ cá nhân, một thương hiệu cần xây dựng sự tin tưởng, trung thực và mang lại giá trị đích thực cho khách hàng. Khi mối quan hệ giữa thương hiệu và người dùng được xây dựng dựa trên lòng tin, sự thấu cảm và những câu chuyện cốt lõi, đó chính là lúc tình yêu thương hiệu được hình thành.\n\nHãy tưởng tượng, khi bạn muốn kết nối với ai đó ở một mức độ sâu sắc, bạn sẽ chia sẻ những điều thân mật về bản thân mình. Và đổi phương thường sẽ đáp lại bằng những câu chuyện cá nhân của họ. Trong thương hiệu cũng vậy, khách hàng sẽ yêu mến những thương hiệu dám chính trực, dám kể những câu chuyện thật, và dám chấp nhận tạo nên những trải nghiệm chân thành và đáng nhớ.\n\nMột thương hiệu thành công không chỉ dựa trên sự kết nối cảm xúc mà còn phải mang lại giá trị thiết thực. Con người không chỉ muốn mua sản phẩm, họ muốn đồng hành cùng những thương hiệu hiểu về mình, chia sẻ những giá trị chung và có khả năng thấu cảm với họ.\n\nCuốn sách \"Human Branding – Nuôi dưỡng thương hiệu bằng sự thấu cảm\" sẽ giúp bạn hiểu rõ cách xây dựng một thương hiệu vững chắc theo thời gian, vừng chắc trong lòng khách hàng và vừng chắc trên thị trường.', 'nding--nuoi-duong-bang-su-thau-cam------full-----t1-2025----outline-02_17ba7d5bdd584466a5895d17371669c1_medium.jpg'),
('DS010', 'Inamori Kazuo Mỗi Ngày Một Câu Nói Nâng Tầm Vận Mệnh', 2021, 95000.00, 'TG010', 'TL001', 'NXB001', 'Inamori Kazuo Mỗi Ngày Một Câu Nói Nâng Tầm Vận Mệnh\n\nCuốn sách Inamori Kazuo Mỗi Ngày Một Câu Nói Nâng Tầm Vận Mệnh là một bộ sưu tập các câu nói, triết lý về cuộc sống và công việc, được chia thành từng ngày trong năm. Mỗi ngày, tác giả chia sẻ một câu nói hoặc một bài học ngắn gọn, thể hiện triết lý sống của ông, khuyến khích Cuốn sách được chia thành 12 chương, tương ứng với 12 tháng trong năm. Mỗi tháng chứa các câu nói và triết lý được trình bày theo từng ngày, với các bài học khác nhau về cuộc sống, công việc, và nhân cách.', '2025_01_13_16_45_47_1-390x510_1ea3a854d455410f948c98b2696f949d_medium.jpg'),
('DS011', 'The AI Edge - Khai Thác Thế Mạnh AI Trong Sales Và Marketing', 2025, 219000.00, 'TG011', 'TL001', 'NXB004', 'The AI Edge - Khai Thác Thế Mạnh AI Trong Sales Và Marketing\n\nTrí tuệ nhân tạo – AI đang có những bước phát triển mạnh mẽ và được ứng dụng ngày càng nhiều trong hầu hết các lĩnh vực từ giáo dục, y tế, tài chính đến sản xuất công nghiệp… Trong lĩnh vực Sale & Marketing, trí tuệ nhân tạo không chỉ nâng cấp quy trình bán hàng mà còn giúp phân tích dữ liệu khách hàng một cách hiệu quả. Từ đó, doanh nghiệp có thể cá nhân hóa trải nghiệm khách hàng và tối ưu hóa chiến dịch quảng cáo.\n\nThế giới bán hàng đang cạnh tranh ngày càng khốc liệt, việc tìm kiếm và tận dụng những lợi thế để vượt lên đối thủ cạnh tranh là vô cùng quan trọng. Hãy tận dụng sức mạnh của trí tuệ nhân tạo để tinh gọn quy trình bán hàng, tối ưu nội dung quảng cáo, giảm thiểu chi phí bán hàng, ra quyết định chiến lược hiệu quả… và The AI Edge: Khai thác thế mạnh AI trong Sales & Marketing sẽ là cuốn sách dẫn đường cho bạn trong hành trình này.\n\nTrong cuốn sách The AI Edge: Khai thác thế mạnh AI trong Sales & Marketing, 2 tác giả Jeb Blount và Anthony Iannarino, đã mở ra một tư duy mới kết hợp các chiến lược bán hàng độc đáo với sức mạnh biến đổi của AI.\n\nCác tác giả làm rõ cách khai thác trí tuệ nhân tạo và chứng minh tiềm năng của nó trong việc giúp bạn có thêm thời gian để phát huy những lợi thế chỉ thuộc về con người: năng lực sáng tạo, khả năng đồng cảm và sự chân thành. Đây chính là yếu tố then chốt khiến bạn trở thành nhân sự không thể thay thế trong kỷ nguyên công nghệ.\n\nVới The AI Edge: Khai thác thế mạnh AI trong Sales & Marketing, bạn có thể khám phá lộ trình để tích hợp AI vào chiến lược bán hàng, cụ thể:\n\n- Tinh gọn quy trình và tương tác hiệu quả: Tìm hiểu vai trò của AI trong việc tự động hóa các nhiệm vụ lặp đi lặp lại, giúp bạn được giải phóng để tập trung vào các khía cạnh độc quyền của con người như xây dựng mối quan hệ và khai phá năng lực sáng tạo.\n\n- Nắm được kỹ thuật thiết kế câu lệnh AI: Thực hành thiết kế câu lệnh phù hợp để tận dụng AI tạo sinh, từ đó đạt được kết quả tối ưu trong thời gian ngắn.\n\n- Xử lý dữ liệu thông minh: Sử dụng AI phân tích dữ liệu và ứng dụng vào các cuộc gặp gỡ với khách hàng, tăng cơ hội giao dịch thành công.\n\n- Tăng cường hiệu quả tìm kiếm khách hàng: Tận dụng sức mạnh của AI để xây dựng danh sách khách hàng tiềm năng chất lượng, mở ra nhiều cơ hội kinh doanh trong khi giảm thiểu nguy cơ bị từ chối.', 'the-ai-edge---bia-1_724ce444630e4d97b4a92074ffe8dea4_medium.jpg'),
('DS012', 'Inbox Marketing', 2022, 198000.00, 'TG012', 'TL001', 'NXB002', 'Tăng Khách Hàng Và\nChốt Sales Với Chatbot\nTiếp thị và bán hàng trò chuyện là một phần của phương pháp luận mới tập trung vào các cuộc trò chuyện trực tiếp với khách hàng trong thời gian thực thông qua chatbot và nhắn tin. \n\nBằng cách cho phép doanh nghiệp của bạn giao tiếp với khách hàng trong thời gian thực — khi thuận tiện nhất cho họ — tiếp thị trò chuyện cải thiện trải nghiệm của khách hàng, tạo ra nhiều khách hàng tiềm năng hơn và giúp bạn chuyển đổi nhiều khách hàng tiềm năng hơn thành khách hàng.\n\nCải Tiến Chiến Lược Bán Hàng\nCác ứng dụng nhắn tin hiện đại, cho phép các cuộc trò chuyện thời gian thực và phản hồi tức thì, đã thay đổi cách chúng ta tương tác trong cuộc sống cá nhân và nghề nghiệp của mình, tuy nhiên hầu hết các doanh nghiệp vẫn dựa vào công nghệ thế kỷ 20 để giao tiếp với khách hàng thế kỷ 21. Các biểu mẫu trực tuyến, các câu hỏi qua email và các cuộc gọi bán hàng tiếp theo không mang lại hiệu quả tức thì mà người tiêu dùng hiện đại mong đợi.', 'inbox_marketing_15afb629ea474cb5a9ad344b439c7308_22276044ff2840b9a6bbab9f0e24f20b_medium.jpg'),
('DS013', 'Thấy Trước Doanh Thu - Aaron Ross, Marylou Tyler', 2024, 250000.00, 'TG013', 'TL001', 'NXB002', 'Đây không phải một cuốn sách khác về cách có những cuộc gọi lạnh hay chốt giao dịch. Đây là toàn bộ kinh thư về bán hàng mới cho CEO những doanh nhân và Phó giám đốc bán hàng để hỗ trợ bạn xây dựng một cỗ máy bán hàng. Những gì có thể giúp đội ngũ bán hàng của bạn tạo ra nhiều khách hàng tiềm năng mứi siêu chất lượng,tạo ra doanh thu thấy trước,và đáp ứng mục tiêu tài chính mà không cần sự tập trung và chú ý liên tục của bạn? Thấy trước doanh thu trả lời?', 'thay_truoc_doanh_thu_6f4812d84de049068dfedc6405a76699_2244be3944224857a078d77494e3f9f9_medium.jpg'),
('DS014', 'Marketing Trực Tiếp - Dan S. Kennedy', 2019, 85000.00, 'TG014', 'TL001', 'NXB002', 'Cẩm Nang Bách Thắng - Marketing Trực Tiếp\n\nTrong bối cảnh hội nhập, sức ép cạnh tranh ngày càng gay gắt, các doanh nghiệp muốn tồn tại cần đặc biệt chú trọng đến các hoạt động Marketing. Marketing không chỉ là một chức năng trong hoạt động kinh doanh, Marketing còn là một triết lý dẫn dắt toàn bộ hoạt động của doanh nghiệp trong việc phát hiện ra, đáp ứng và làm thỏa mãn nhu cầu của khách hàng. Nhờ có các hoạt động Marketing, các doanh nghiệp hiểu hơn về người tiêu dùng, thỏa mãn nhu cầu và nguyện vọng của họ qua đó sẽ làm cho việc sản xuất kinh doanh đạt hiệu quả cao hơn.\n\nĐể các doanh nghiệp, cá nhân có được tài liệu tham khảo hữu ích liên quan đến lĩnh vực marketing và bán hàng, Nhà xuất bản Thế Giới cho phát hành cuốn sách:\n\nHãy khám phá những thủ thuật thu hút khách hàng, đẩy mạnh doanh thu mà bạn chưa từng biết đến cũng như phương pháp ứng dụng hiệu quả 10 quy tắc giúp chuyển đổi doanh nghiệp của bạn thành một doanh nghiệp marketing trực tiếp với hiệu quả cao hơn nhiều\n\nQuy tắc 1 . Luôn luôn có một hoặc nhiều chương trình khuyến mãi\n\nQuy tắc 2. Đưa ra lý do để khách hàng phản hồi ngay lập tức\n\nQuy tắc 3. Đưa ra chỉ dẫn cụ thể\n\nQuy tắc 4. Phải có phương thức theo dõi, đo lường và kiểm định hiệu quả\n\nQuy tắc 5. Xây dựng thương hiệu, nhưng không tốn tiền\n\nQuy tắc 6. Phải theo dõi và nhắc nhở\n\nQuy tắc 7. Nội dung phải thật ấn tượng\n\nQuy tắc 8. Hình thức giống quảng cáo qua thư\n\nQuy tắc 9. Kết quả là trên hết. Chấm Hết\n\nQuy Tắc 10. Bạn phải là một người quản lý cực kỳ cương quyết để rèn doanh nghiệp của mình theo đúng định hướng Marketing trực tiếp.', 'marketing_truc_tiep_0181e1af8c2d4d009ece017382dccb53_cbb0f857539f4305874b787103978ccd_medium.jpg'),
('DS015', 'Bán Hàng Cho Người Giàu - Dan S Kennedy', 2018, 79000.00, 'TG014', 'TL001', 'NXB002', 'Cẩm Nang Bách Thắng - Bán Hàng Cho Người Giàu (Tái Bản)\n\nSỰ THẬT ĐÁNG SỢ : Tầng lớp trung lưu- và những khoản chi tiêu của họ- đang biến mất với tốc độ cực kỳ nhanh chóng trên diện rộng. Khách hàng giờ đây mua hàng ít hơn và chi tiêu cho một số ít, các lĩnh vực tiêu dùng.\n\nCƠ HỘI DÀNH CHO BẠN : Giờ đây bạn không còn phải làm việc cật lực để lôi kéo khách hàng từ những tầng lớp khá giả, giàu có và siêu giàu vì nhóm khách hàng nàyhiện đang bùng nổ về số lượng và luôn luôn sẵn sàng chi trả mức giá cao cấp để có được để có được các dịch vụ đẳng cấp, những chuyên gia tên tuổi và những trải nghiệm độc đáo.\n\nTrong quyển sách này, triệu phú DAN Kennedy, cùng với những chuyên gia xây dựng thương hiệu là Nick Nanton, J.W. Dicks và đội ngũ của mình sẽ trình bày cho bạn phương pháp để tái định vị doanh nghiệp, cửa hàng hoặc sự nghiệp bán hàng của mình, giúp bạn thu hút những khách hàng không quan tâm đến mức giá khi mua hàng. Hãy đọc cách để bán hàng cho những người luôn luôn có tiền để cho trả.', 'ban_hang_cho_nguoi_giau_4ba2acb9b52742eea9151eac9a7231af_61bcd38d676d49b59c1a94c9e29f8eed_medium.jpg'),
('DS016', 'Bí Mật Traffic - Russell Brunson', 2023, 185000.00, 'TG015', 'TL001', 'NXB002', 'Bí mật - Traffic - Bìa Cứng\n\nVấn đề lớn nhất mà các doanh nhân gặp phải không phải là họ không có sản phẩm hay dịch vụ tuyệt vời; vấn đề đó là việc họ không khiến cho khách hàng tương lai của họ biết đến sự tồn tại sản phẩm dịch vụ của họ. Mỗi năm, có hàng nghìn công ty được thành lập và phá sản bởi vì các doanh nhân không hiểu được kỹ năng vô cùng thiết yếu: nghệ thuật và khoa học để khiến cho traffic (con người) tìm đến bạn.\n\nVà đó thực sự là một thảm kịch.\n\nBí Mật Traffic được viết ra để giúp bạn đưa thông điệp về sản phẩm và dịch vụ của bạn đến được với thế giới. Tôi có niềm tin mạnh mẽ rằng các doanh nhân là những người duy nhất trên thế giới này có thể thực sự thay đổi thế giới. Nhiệm vụ này không thuộc về các chính phủ và tôi cũng không tin rằng nó có thể được thực hiện bởi các trường học.\n\nĐiều này xảy ra bởi vì những doanh nhân như bạn, người có đủ sự điên rồ đủ lớn để xây dựng sản phẩm và dịch vụ có thể thay đổi thế giới. Nó sẽ xảy ra bởi vì chúng ta đủ điên rồ để mạo hiểm mọi thứ với mục đích biến ước mơ trở thành hiện thực.\n\nTôi muốn gửi đến tất cả các doanh nhân đã từng thất bại trong những năm đầu tiên khởi nghiệp, rằng đó là một bi kịch khi một người đã sẵn sàng mạo hiểm với mọi thứ họ có nhưng lại không bao giờ nhận lại được đầy đủ những gì họ xứng đáng.\n\nChờ đợi mọi người tìm đến bạn không phải là một chiến lược.\n\nHiểu được chính xác AI là khách hàng lý tưởng của bạn, khám phá xem họ đang Ở ĐÂU, và thả những MỖI CÂU sắc bén để bắt được sự quan tâm của họ rồi kéo họ vào hệ thống PHỄU của bạn (nơi bạn sẽ bắt đầu kể cho họ câu chuyện và đưa ra lời chào hàng) là một chiến lược. Đó là một Bí MẬT lớn.\n\nTraffic cũng là con người. Cuốn sách này sẽ giúp bạn tìm ra được NGƯỜI của bạn, sau đó bạn có thể tập trung thay đổi thế giới thông qua sản phẩm và dịch vụ mà bạn cung cấp.', 'bi_mat_-_traffic_-_bia_cung_f21cc7cf71894c5fa8a6a1aae4d1aca2_61c81c3e2fa347d0925a6d13ab8f8d4a_medium.jpg'),
('DS017', 'Phân Tích Dữ Liệu Marketing', 2022, 350000.00, 'TG016', 'TL001', 'NXB002', 'TEXTBOOK - PHÂN TÍCH DỮ LIỆU MARKETING\n\nChiến lược marketing hiệu quả: Tầm nhìn dựa trên dữ liệu\n\n \n\nĐể đạt được thành công, một doanh nghiệp cần có chiến lược marketing rõ ràng và quy trình phân tích dữ liệu hiệu quả. Là người đứng đầu doanh nghiệp, bạn phải nắm rõ lĩnh vực đầu tư, đồng thời dựa vào dữ liệu để xây dựng tầm nhìn và chiến lược phát triển. Đây là yếu tố then chốt giúp doanh nghiệp duy trì mối quan hệ với khách hàng và đảm bảo sự phát triển bền vững. Do đó, việc phân tích dữ liệu trong marketing không chỉ cần thiết mà còn đóng vai trò quan trọng trong việc định hình con đường kinh doanh và tối ưu hóa hiệu quả hoạt động.\n\n \n\nPhân tích dữ liệu trong marketing đóng vai trò then chốt trong suốt quá trình từ hoạch định chiến lược, triển khai đến đánh giá hiệu quả dự án. Thay vì chỉ dựa vào trực giác hay kinh nghiệm, doanh nghiệp có thể tận dụng dữ liệu để xây dựng chiến lược marketing tối ưu, từ việc thấu hiểu hành vi, sở thích và nhu cầu của khách hàng, đến việc chọn lựa kênh tiếp thị phù hợp, đo lường và tối ưu hóa hiệu suất chiến dịch.\n\n \n\nCách tiếp cận này không chỉ nâng cao khả năng thành công của các chiến dịch marketing mà còn tăng cường sức cạnh tranh trên thị trường, giúp doanh nghiệp nhanh chóng đạt được mục tiêu kinh doanh.\n\n \n\nTextbook Phân tích dữ liệu Marketing – Chìa khóa dẫn lối thành công cho chiến lược marketing\n\nCuốn sách Phân tích dữ liệu marketing là một tài liệu không thể thiếu cho bất kỳ ai muốn khám phá lĩnh vực phân tích marketing. Cuốn sách này khéo léo kết nối giữa lý thuyết và thực tiễn, cung cấp cho người đọc sự hiểu biết toàn diện về các khái niệm và công cụ cần thiết cho việc ra quyết định marketing hiệu quả.\n\n \n\nMột trong những điểm nổi bật của cuốn sách là cách giải thích các kỹ thuật phân tích phức tạp một cách đơn giản và dễ hiểu, giúp người đọc dễ tiếp cận ngay cả khi họ chưa biết nhiều về kỹ thuật phân tích dữ liệu. Mỗi chương đều có bổ sung các ví dụ thực tế và tình huống nghiên cứu để minh họa cách áp dụng các kỹ thuật phân tích trong các kịch bản kinh doanh khác nhau.\n\n \n\nNgoài ra, Textbook Phân tích dữ liệu Marketing còn cung cấp nhiều bài tập thực hành cùng bộ dữ liệu thực tế, giúp người đọc dễ dàng áp dụng kiến thức vừa học. Phương pháp tiếp cận này không chỉ củng cố các khái niệm lý thuyết mà còn rèn luyện kỹ năng phân tích, đồng thời nâng cao sự tự tin trong việc sử dụng các công cụ phân tích marketing vào các tình huống thực tế.\n\n \n\nMột số lời đánh giá của các chuyên gia về sách:\n\n“Cuốn sách “Phân tích dữ liệu marketing” còn đưa ra các phương pháp chi tiết nhất, giúp bạn nắm rõ những cách khai thác nguồn dữ liệu hiệu quả trong marketing để nâng cao chiến lược tiếp cận khách hàng. Cuốn sách này như một lời khẳng định rằng, phân tích dữ liệu là điều bắt buộc để các doanh nghiệp có thể sinh tồn và phát triển trong thời đại số.”\n\nÔng Lê Trí Thông – Tổng Giám đốc Công ty Cổ phần Vàng bạc Đá quý Phú Nhuận (PNJ) nhận xét về sách.\n\n \n\n“Đối với bất kỳ ai nghiêm túc muốn nắm vững phân tích marketing, cuốn sách này là một công cụ quan trọng để đưa ra các quyết định thông minh và thúc đẩy hoạt động kinh doanh thông qua việc sử dụng phân tích hiệu quả.”\n\nÔng Nguyễn Phúc Trọng – Giám đốc Phân tích tại Tiki & Đồng sáng lập Trường Công\n\nnghệ Animum', 'ph_n_t_ch_d_li_u_marketing_-_b_a_1_5fcbefdf10ac4cddbd395acd581ba96c_medium.jpg'),
('DS018', 'Trò Chơi Tâm Lý Trong Marketing', 2021, 110000.00, 'TG017', 'TL001', 'NXB004', 'Trò Chơi Tâm Lý Trong Marketing\n\nChúng ta có bị ảnh hưởng bởi quảng cáo kể cả khi nhanh chóng lướt qua chúng? Các thương hiệu có mở rộng những cá tính của ta không? Vì sao chúng ta chi tiêu nhiều hơn khi trả bằng thẻ tín dụng?\n\nTrong thời kỳ hiện đại, việc mua và sở hữu của cải vật chất, cùng với sự phát triển của ngành dịch vụ khác nhau, đã trở thành trung tâm trong nhận thức của con người về sự tồn tại, bên cạnh gia đình và sự nghiệp.\n\nCuốn sách TRÒ CHƠI TÂM LÝ TRONG MARKETING xem xét tác động của tâm lý học đến thực tiễn marketing và nghiên cứu marketing, làm nổi bật những khía cạnh đã được ứng dụng của nghiên cứu tâm lý học vào thị trường.\n\nMỗi chương bàn đến một lĩnh vực chủ đề quan trọng trong tâm lý học, liệt kê những lý thuyết chính, và giới thiệu nhiều ứng dụng thực tiễn của nghiên cứu. Những khái niệm chính được nhấn mạnh và những « case study » liên quan đến tài liệu được nêu ở cuối mỗi chương.\n\n- Những lĩnh vực được xét đến gồm có:\n\n● Động lực: Những nhu cầu của con người là nguồn gốc nhiều hành vi của người tiêu dùng và những quyết định trong marketing.\n\n● Nhận thức: Bản chất của sự lựa chọn, sự chú ý và sự sắp xếp mang tính nhận thức và mối quan hệ của chúng đối với lĩnh vực marketing đang ngày càng phát triển.\n\n● Ra quyết định: Bằng cách nào và trong những tình huống nào mà ta có thể thể dự đoán lựa chọn, thái độ của người tiêu dùng và cách thuyết phục họ.\n\n● Cá tính và phong cách sống: Cách vận dụng những kiến thức sâu sắc về cá tính của người tiêu dùng để tạo ra các kế hoạch marketing.\n\n● Hành vi xã hội: Vai trò to lớn của sự ảnh hưởng của xã hội với hoạt động tiêu dùng.\n\nCuốn sách này sẽ có được sự quan tâm lớn từ nhiều đối tượng độc giả như các nhà nghiên cứu, các sinh viên và những chuyên gia, và sẽ là tài liệu bắt buộc cho những khóa học về marketing, tâm lý học, hành vi tiêu dùng và quảng cáo.', 'tro-choi-tam-ly-trong-marketing_e4254fea668e4f86aaac77269595bee5_medium.jpg'),
('DS019', 'Nghệ Thuật Bán Hàng Của Người Hướng Nội - Trở Thành Số 1 Bán Hàng Khi Là Người Nhút Nhát', 2020, 95000.00, 'TG019', 'TL001', 'NXB005', 'Nghệ Thuật Bán Hàng Của Người Hướng Nội - Trở Thành Số 1 Bán Hàng Khi Là Người Nhút Nhát\n\nQuy trình bán hàng gồm 7 bước giúp tận dụng các ưu điểm bẩm sinh của người hướng nội để bán hàng thành công và vượt cả những người hướng ngoại.\n\nCuốn sách này sẽ chỉ cho các bạn cách:\n\nTìm được sự tự tin có sẵn trong bạn\n\nChuẩn bị cho mọi tình huống\n\nThể hiện giá trị mà khách hàng mong muốn\n\nTránh được sự từ chối\n\nPhán đoán thời điểm khách hàng sẵn sàng mua hàng\n\nVà nhiều điều nữa trong đó không thể thiếu việc tận hưởng thành quả bán hàng của bạn!', '8935235243231_7195ea3c5e9f4c548f374831c9035533_medium.jpg'),
('DS020', 'GAM7 BOOK SPECIAL 2020 - Marketing Thời Bình Thường Mới - Sẵn Sàng Chuyển Dịch Để Vươn Lên', 2019, 165000.00, 'TG012', 'TL001', 'NXB005', 'GAM7 Special 2020 lấy bối cảnh của đại dịch COVID-19, tập trung phân tích giải pháp marketing nhằm giúp doanh nghiệp đi lên. Ở chủ đề đặc biệt này, hoạt động marketing được gắn trực tiếp với sự dịch chuyển của khách hàng và thị trường ở thời bình thường mới với những CÁI NHẤT không thể bỏ qua:\n\n▶ SỐ GAM7 ĐẶC BIỆT NHẤT với duy nhất 01 ấn phẩm được xuất bản trong năm\n\n▶ Ấn phẩm với sự tham gia của NHIỀU CỐ VẤN CHUYÊN MÔN NHẤT từ trước tới nay\n\n▶ Số lượng TRANG NỘI DUNG VƯỢT TRỘI NHẤT với 160 trang thay vì 124 trang như các số trước\n\n▶ Chủ đề mang tính ảnh hưởng TOÀN CẦU với đa dạng góc nhìn thực tiễn cho doanh nghiệp tại Việt Nam\n\nBên cạnh đề cập trực tiếp sự thay đổi của các hoạt động marketing, các bài viết mở rộng khai thác tới các yếu tố cốt lõi tạo ra những định hướng phát triển mới trong doanh nghiệp, như sản phẩm, mô hình kinh doanh, bộ máy doanh nghiệp,... với 03 theme nội dung:\n\n▶THEME 1: BỨC TRANH TOÀN CẢNH - Cho thấy động thái của các doanh nghiệp; diễn biến tâm lý; và phản ứng của khách hàng qua từng giai đoạn của đại dịch.\n\n▶THEME 2: ỨNG BIẾN LINH HOẠT - Là hàng loạt giải pháp tức thời nhưng cương quyết đã được đưa ra để phản ứng nhanh với đại dịch.\n\n▶THEME 3: SẴN SÀNG CHO TƯƠNG LAI - Sẵn sàng cho tương lai gần, khi đại dịch kết thúc, và cả tương lai rất xa.', 'image_195509_1_45100_1_f305e36ac4bd44c68cd62c7c6daacba2_medium.jpg'),
('DS021', 'Conversion Hacking - Gia Tăng Tỷ Lệ Chốt Đơn Hàng', 2024, 140000.00, 'TG020', 'TL001', 'NXB005', 'Conversion Hacking - Gia Tăng Tỷ Lệ Chốt Đơn Hàng\n\nĐối mặt với KPI doanh thu mỗi ngày, những người làm việc trực tiếp với Digital Marketing luôn đau đầu với những câu hỏi: Làm thế nào để gia tăng lượng khách hàng thanh toán? Cách giảm chi phí chạy quảng cáo? Tối ưu lợi nhuận trên đơn hàng như thế nào?.. \n\nHiểu được điều này, RIO Book cùng tác giả Bình Nguyễn - Founder/CEO LadiPage và Việt Anh Nori - Founder ECOMME xuất bản cuốn sách Conversion Hacking - Gia tăng tỷ lệ chốt đơn hàng giúp bạn:\n\n - Chốt được nhiều đơn hàng thành công\n\n- Trả lời câu hỏi vì sao khách hàng không mua hàng của bạn\n\n- Tìm kiếm và gia tăng đúng lượng khách hàng sẽ mua sản phẩm\n\n- Gắn kết với khách hàng cũ khiến họ sẵn sàng quay trở lại\n\n- Giảm chi phí, tối ưu hoá doanh thu và gia tăng lợi nhuận trực tiếp\n\n- Mở rộng tư duy đa kênh và vận hành các kênh Digital Marketing\n\n- Thực hành lên kế hoạch Digital Marketing Plan với ấn phẩm tặng kèm đặc biệt\n\nĐừng bỏ lỡ Conversion Hacking - Bí kíp gia tăng tỷ lệ chốt đơn hàng của bạn.', 'untitled__8__307f3c28ba554e09b11107a5c1f716e1_medium.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `docgia`
--

CREATE TABLE `docgia` (
  `madocgia` varchar(50) NOT NULL,
  `hodocgia` varchar(100) DEFAULT NULL,
  `tendocgia` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `sdt` varchar(20) DEFAULT NULL,
  `ngaysinh` date DEFAULT NULL,
  `diachi` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nhacungcap`
--

CREATE TABLE `nhacungcap` (
  `mancc` varchar(50) NOT NULL,
  `tenncc` varchar(255) DEFAULT NULL,
  `diachincc` varchar(255) DEFAULT NULL,
  `sdt` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nhacungcap`
--

INSERT INTO `nhacungcap` (`mancc`, `tenncc`, `diachincc`, `sdt`, `email`) VALUES
('NCC_FAHASA', 'Công ty Cổ phần Phát hành Sách TP.HCM - FAHASA', '60-62 Lê Lợi, Quận 1, TP.HCM', '1900636467', 'info@fahasa.com.vn'),
('NCC_NHANAM', 'Công ty Cổ phần Văn hóa và Truyền thông Nhã Nam', '59 Đỗ Quang, Trung Hòa, Cầu Giấy, Hà Nội', '02435146205', 'info@nhanam.vn'),
('NCC_PHUONGNAM', 'Công ty Cổ phần Văn hóa Phương Nam', '940 Đường 3/2, Phường 15, Quận 11, TP.HCM', '19006656', 'hotro@phuongnambook.com'),
('NCC_SAIGONBOOKS', 'Công ty Cổ phần Sách Sài Gòn (Saigon Books)', '97 Nguyễn Bỉnh Khiêm, Đa Kao, Quận 1, TP.HCM', '02862822666', 'contact@saigonbooks.vn'),
('NCC_THAIHA', 'Công ty Cổ phần Sách Thái Hà (ThaiHaBooks)', '119 C3, Tập thể Mai Động, Hoàng Mai, Hà Nội', '02437533909', 'info@thaihabooks.com'),
('NCC_TRE', 'Nhà xuất bản Trẻ', '161 Lý Chính Thắng, Phường Võ Thị Sáu, Quận 3, TP.HCM', '02839316237', 'tre@nxbtre.com.vn');

-- --------------------------------------------------------

--
-- Table structure for table `nhanvien`
--

CREATE TABLE `nhanvien` (
  `manv` varchar(50) NOT NULL,
  `honv` varchar(100) DEFAULT NULL,
  `tennv` varchar(100) DEFAULT NULL,
  `gioitinh` varchar(10) DEFAULT NULL,
  `sdt` varchar(20) DEFAULT NULL,
  `ngaysinh` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nhaxuatban`
--

CREATE TABLE `nhaxuatban` (
  `manxb` varchar(50) NOT NULL,
  `tennxb` varchar(255) DEFAULT NULL,
  `diachi` varchar(255) DEFAULT NULL,
  `sdt` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nhaxuatban`
--

INSERT INTO `nhaxuatban` (`manxb`, `tennxb`, `diachi`, `sdt`, `email`) VALUES
('NXB001', 'NXB Trẻ', '161 Lý Chính Thắng, Võ Thị Sáu, Quận 3, TP.HCM', '02839316237', 'tre@nxbtre.com.vn'),
('NXB002', 'NXB Thế Giới', '55 Quang Trung, Hà Nội', '02439434730', 'info@nxbkimdong.com.vn'),
('NXB003', 'NXB Thông Tấn', '11 Trần Hưng Đạo, Hoàn Kiếm, Hà Nội', '02439331149', 'vnanet@vnanet.vn'),
('NXB004', 'NXB Công Thương', 'Hà Nội', '02400000000', 'info@nxbcongthuong.vn'),
('NXB005', 'NXB Dân Trí', 'Hà Nội', '02400000001', 'info@nxbdantri.vn');

-- --------------------------------------------------------

--
-- Table structure for table `nhomquyen`
--

CREATE TABLE `nhomquyen` (
  `manhomquyen` varchar(50) NOT NULL,
  `tennhomquyen` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phieumuon`
--

CREATE TABLE `phieumuon` (
  `mamuon` varchar(50) NOT NULL,
  `ngaymuon` datetime DEFAULT NULL,
  `ngayhethan` datetime DEFAULT NULL,
  `manv` varchar(50) DEFAULT NULL,
  `madocgia` varchar(50) DEFAULT NULL,
  `trangthai` varchar(50) DEFAULT NULL,
  `ghichu` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phieunhap`
--

CREATE TABLE `phieunhap` (
  `maphieunhap` varchar(50) NOT NULL,
  `thoigiantao` datetime DEFAULT NULL,
  `tongtien` decimal(10,2) DEFAULT NULL,
  `manv` varchar(50) DEFAULT NULL,
  `mancc` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phieuphat`
--

CREATE TABLE `phieuphat` (
  `maphat` varchar(50) NOT NULL,
  `madocgia` varchar(50) DEFAULT NULL,
  `matra` varchar(50) DEFAULT NULL,
  `ngaylap` datetime DEFAULT NULL,
  `tongtienphat` decimal(10,2) DEFAULT NULL,
  `trangthai` varchar(50) DEFAULT NULL,
  `ghichu` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phieutra`
--

CREATE TABLE `phieutra` (
  `matra` varchar(50) NOT NULL,
  `mamuon` varchar(50) DEFAULT NULL,
  `ngaytra` datetime DEFAULT NULL,
  `manv` varchar(50) DEFAULT NULL,
  `tongtienphat` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tacgia`
--

CREATE TABLE `tacgia` (
  `matacgia` varchar(50) NOT NULL,
  `tentacgia` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tacgia`
--

INSERT INTO `tacgia` (`matacgia`, `tentacgia`) VALUES
('TG001', 'Bách Tùng'),
('TG002', '	\nLeader Thanh'),
('TG003', ' Joe Vitale'),
('TG004', 'Andrea Fryrear'),
('TG005', 'Khôi'),
('TG006', 'Richard Moore'),
('TG007', ' Matt Johnson PhD, Prince Ghuman'),
('TG008', 'Dan White'),
('TG009', ' Lydia Michael'),
('TG010', 'Inamori Kazuo'),
('TG011', 'Jeb Blount, Anthony Iannarino'),
('TG012', 'Nhiều tác giả'),
('TG013', 'Aaron Ross, Marylou Tyler'),
('TG014', 'Dan S. Kennedy'),
('TG015', ' Russell Brunson'),
('TG016', ' Joseph F. Hair, Jr., Dana E. Harrison, Haya Ajjan'),
('TG017', 'Allan J. Kimmel'),
('TG018', 'Mary Roach'),
('TG019', 'Alex Goldfayn'),
('TG020', 'Bình Nguyễn, Việt Anh Nori'),
('TG021', ''),
('TG022', ''),
('TG023', ''),
('TG024', ''),
('TG025', ''),
('TG026', ''),
('TG027', ''),
('TG028', ''),
('TG029', ''),
('TG030', '');

-- --------------------------------------------------------

--
-- Table structure for table `taikhoan`
--

CREATE TABLE `taikhoan` (
  `tendangnhap` varchar(50) NOT NULL,
  `matkhau` varchar(255) DEFAULT NULL,
  `manhomquyen` varchar(50) DEFAULT NULL,
  `manv` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `theloai`
--

CREATE TABLE `theloai` (
  `matheloai` varchar(50) NOT NULL,
  `tentheloai` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `theloai`
--

INSERT INTO `theloai` (`matheloai`, `tentheloai`) VALUES
('TL001', 'Kinh Tế'),
('TL002', 'Văn Học Trong Nước'),
('TL003', 'Văn Học Nước Ngoài'),
('TL004', 'Thưởng Thức Đời Sống'),
('TL005', 'Thiếu Nhi'),
('TL006', 'Phát Triển Bản Thân'),
('TL007', 'Tin Học Ngoại Ngữ');

-- --------------------------------------------------------

--
-- Table structure for table `vitri`
--

CREATE TABLE `vitri` (
  `mavitri` varchar(50) NOT NULL,
  `khuvuc` varchar(100) DEFAULT NULL,
  `ke` varchar(50) DEFAULT NULL,
  `mota` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vitri`
--

INSERT INTO `vitri` (`mavitri`, `khuvuc`, `ke`, `mota`) VALUES
('VT001', 'Tầng 1', 'Kệ A1', 'Khu vực văn học'),
('VT002', 'Tầng 1', 'Kệ B2', 'Khu vực trinh thám'),
('VT003', 'Tầng 2', 'Kệ C1', 'Khu vực kỹ năng');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ctphieumuon`
--
ALTER TABLE `ctphieumuon`
  ADD PRIMARY KEY (`mamuon`,`macuonsach`),
  ADD KEY `fk_ctpm_cuonsach` (`macuonsach`);

--
-- Indexes for table `ctphieunhap`
--
ALTER TABLE `ctphieunhap`
  ADD PRIMARY KEY (`maphieunhap`,`madausach`),
  ADD KEY `fk_ctpn_dausach` (`madausach`);

--
-- Indexes for table `ctphieuphat`
--
ALTER TABLE `ctphieuphat`
  ADD PRIMARY KEY (`maphat`,`macuonsach`),
  ADD KEY `fk_ctpp_cuonsach` (`macuonsach`);

--
-- Indexes for table `ctphieutra`
--
ALTER TABLE `ctphieutra`
  ADD PRIMARY KEY (`matra`,`macuonsach`,`maphat`),
  ADD KEY `fk_ctpt_cuonsach` (`macuonsach`);

--
-- Indexes for table `ctquyen`
--
ALTER TABLE `ctquyen`
  ADD PRIMARY KEY (`manhomquyen`,`machucnang`),
  ADD KEY `fk_ctq_chucnang` (`machucnang`);

--
-- Indexes for table `cuonsach`
--
ALTER TABLE `cuonsach`
  ADD PRIMARY KEY (`macuonsach`),
  ADD KEY `fk_cuonsach_dausach` (`madausach`),
  ADD KEY `fk_cuonsach_vitri` (`mavitri`);

--
-- Indexes for table `danhmucchucnang`
--
ALTER TABLE `danhmucchucnang`
  ADD PRIMARY KEY (`machucnang`);

--
-- Indexes for table `dausach`
--
ALTER TABLE `dausach`
  ADD PRIMARY KEY (`madausach`),
  ADD KEY `fk_dausach_tacgia` (`matacgia`),
  ADD KEY `fk_dausach_theloai` (`matheloai`),
  ADD KEY `fk_dausach_nxb` (`manxb`);

--
-- Indexes for table `docgia`
--
ALTER TABLE `docgia`
  ADD PRIMARY KEY (`madocgia`);

--
-- Indexes for table `nhacungcap`
--
ALTER TABLE `nhacungcap`
  ADD PRIMARY KEY (`mancc`);

--
-- Indexes for table `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD PRIMARY KEY (`manv`);

--
-- Indexes for table `nhaxuatban`
--
ALTER TABLE `nhaxuatban`
  ADD PRIMARY KEY (`manxb`);

--
-- Indexes for table `nhomquyen`
--
ALTER TABLE `nhomquyen`
  ADD PRIMARY KEY (`manhomquyen`);

--
-- Indexes for table `phieumuon`
--
ALTER TABLE `phieumuon`
  ADD PRIMARY KEY (`mamuon`),
  ADD KEY `fk_phieumuon_docgia` (`madocgia`),
  ADD KEY `fk_phieumuon_nhanvien` (`manv`);

--
-- Indexes for table `phieunhap`
--
ALTER TABLE `phieunhap`
  ADD PRIMARY KEY (`maphieunhap`),
  ADD KEY `fk_phieunhap_nhacungcap` (`mancc`),
  ADD KEY `fk_phieunhap_nhanvien` (`manv`);

--
-- Indexes for table `phieuphat`
--
ALTER TABLE `phieuphat`
  ADD PRIMARY KEY (`maphat`),
  ADD KEY `fk_phieuphat_docgia` (`madocgia`),
  ADD KEY `fk_phieuphat_phieutra` (`matra`);

--
-- Indexes for table `phieutra`
--
ALTER TABLE `phieutra`
  ADD PRIMARY KEY (`matra`),
  ADD KEY `fk_phieutra_phieumuon` (`mamuon`),
  ADD KEY `fk_phieutra_nhanvien` (`manv`);

--
-- Indexes for table `tacgia`
--
ALTER TABLE `tacgia`
  ADD PRIMARY KEY (`matacgia`);

--
-- Indexes for table `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD PRIMARY KEY (`tendangnhap`),
  ADD KEY `fk_taikhoan_nhanvien` (`manv`),
  ADD KEY `fk_taikhoan_nhomquyen` (`manhomquyen`);

--
-- Indexes for table `theloai`
--
ALTER TABLE `theloai`
  ADD PRIMARY KEY (`matheloai`);

--
-- Indexes for table `vitri`
--
ALTER TABLE `vitri`
  ADD PRIMARY KEY (`mavitri`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ctphieumuon`
--
ALTER TABLE `ctphieumuon`
  ADD CONSTRAINT `fk_ctpm_cuonsach` FOREIGN KEY (`macuonsach`) REFERENCES `cuonsach` (`macuonsach`),
  ADD CONSTRAINT `fk_ctpm_phieumuon` FOREIGN KEY (`mamuon`) REFERENCES `phieumuon` (`mamuon`);

--
-- Constraints for table `ctphieunhap`
--
ALTER TABLE `ctphieunhap`
  ADD CONSTRAINT `fk_ctpn_dausach` FOREIGN KEY (`madausach`) REFERENCES `dausach` (`madausach`),
  ADD CONSTRAINT `fk_ctpn_phieunhap` FOREIGN KEY (`maphieunhap`) REFERENCES `phieunhap` (`maphieunhap`);

--
-- Constraints for table `ctphieuphat`
--
ALTER TABLE `ctphieuphat`
  ADD CONSTRAINT `fk_ctpp_cuonsach` FOREIGN KEY (`macuonsach`) REFERENCES `cuonsach` (`macuonsach`),
  ADD CONSTRAINT `fk_ctpp_phieuphat` FOREIGN KEY (`maphat`) REFERENCES `phieuphat` (`maphat`);

--
-- Constraints for table `ctphieutra`
--
ALTER TABLE `ctphieutra`
  ADD CONSTRAINT `fk_ctpt_cuonsach` FOREIGN KEY (`macuonsach`) REFERENCES `cuonsach` (`macuonsach`),
  ADD CONSTRAINT `fk_ctpt_phieutra` FOREIGN KEY (`matra`) REFERENCES `phieutra` (`matra`);

--
-- Constraints for table `ctquyen`
--
ALTER TABLE `ctquyen`
  ADD CONSTRAINT `fk_ctq_chucnang` FOREIGN KEY (`machucnang`) REFERENCES `danhmucchucnang` (`machucnang`),
  ADD CONSTRAINT `fk_ctq_nhomquyen` FOREIGN KEY (`manhomquyen`) REFERENCES `nhomquyen` (`manhomquyen`);

--
-- Constraints for table `cuonsach`
--
ALTER TABLE `cuonsach`
  ADD CONSTRAINT `fk_cuonsach_dausach` FOREIGN KEY (`madausach`) REFERENCES `dausach` (`madausach`),
  ADD CONSTRAINT `fk_cuonsach_vitri` FOREIGN KEY (`mavitri`) REFERENCES `vitri` (`mavitri`);

--
-- Constraints for table `dausach`
--
ALTER TABLE `dausach`
  ADD CONSTRAINT `fk_dausach_nxb` FOREIGN KEY (`manxb`) REFERENCES `nhaxuatban` (`manxb`),
  ADD CONSTRAINT `fk_dausach_tacgia` FOREIGN KEY (`matacgia`) REFERENCES `tacgia` (`matacgia`),
  ADD CONSTRAINT `fk_dausach_theloai` FOREIGN KEY (`matheloai`) REFERENCES `theloai` (`matheloai`);

--
-- Constraints for table `phieumuon`
--
ALTER TABLE `phieumuon`
  ADD CONSTRAINT `fk_phieumuon_docgia` FOREIGN KEY (`madocgia`) REFERENCES `docgia` (`madocgia`),
  ADD CONSTRAINT `fk_phieumuon_nhanvien` FOREIGN KEY (`manv`) REFERENCES `nhanvien` (`manv`);

--
-- Constraints for table `phieunhap`
--
ALTER TABLE `phieunhap`
  ADD CONSTRAINT `fk_phieunhap_nhacungcap` FOREIGN KEY (`mancc`) REFERENCES `nhacungcap` (`mancc`),
  ADD CONSTRAINT `fk_phieunhap_nhanvien` FOREIGN KEY (`manv`) REFERENCES `nhanvien` (`manv`);

--
-- Constraints for table `phieuphat`
--
ALTER TABLE `phieuphat`
  ADD CONSTRAINT `fk_phieuphat_docgia` FOREIGN KEY (`madocgia`) REFERENCES `docgia` (`madocgia`),
  ADD CONSTRAINT `fk_phieuphat_phieutra` FOREIGN KEY (`matra`) REFERENCES `phieutra` (`matra`);

--
-- Constraints for table `phieutra`
--
ALTER TABLE `phieutra`
  ADD CONSTRAINT `fk_phieutra_nhanvien` FOREIGN KEY (`manv`) REFERENCES `nhanvien` (`manv`),
  ADD CONSTRAINT `fk_phieutra_phieumuon` FOREIGN KEY (`mamuon`) REFERENCES `phieumuon` (`mamuon`);

--
-- Constraints for table `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD CONSTRAINT `fk_taikhoan_nhanvien` FOREIGN KEY (`manv`) REFERENCES `nhanvien` (`manv`),
  ADD CONSTRAINT `fk_taikhoan_nhomquyen` FOREIGN KEY (`manhomquyen`) REFERENCES `nhomquyen` (`manhomquyen`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
